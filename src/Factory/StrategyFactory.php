<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Mvc
 */

namespace ZSmarty\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Resolver\TemplatePathStack;
use ZSmarty\SmartyStrategy;
use ZSmarty\SmartyRenderer;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper as BaseServerUrlHelper;
use Zend\Expressive\Helper\UrlHelper as BaseUrlHelper;
use Zend\Expressive\ZendView\UrlHelper;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\ZendView\ZendViewRenderer;
use Zend\View\HelperPluginManager;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;
use Zend\Expressive\ZendView\NamespacedPathStackResolver;

class StrategyFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sr = $serviceLocator->get('ZSmarty\SmartyRenderer');
        
        $config = $serviceLocator->get('Config');
        
        // pull template names to resolve later
        if ( array_key_exists('view_manager', $config) &&
            array_key_exists('template_map', $config['view_manager']) )
        {
            $layouts = $config['view_manager']['template_map'];
        }
        else
        {
            $layouts = array();
        }
        
        return new SmartyStrategy($sr, $layouts);
    }
    
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = NULL)
    {
        $config   = $container->has('config') ? $container->get('config') : [];
        $config   = isset($config['templates']) ? $config['templates'] : [];

        // Configuration
        $resolver = new Resolver\AggregateResolver();
        $resolver->attach(
            new Resolver\TemplateMapResolver(isset($config['map']) ? $config['map'] : []),
            100
        );
        $resolver->attach(
            new NamespacedPathStackResolver(['default_suffix' => 'tpl']),
            0
        );

        // Create the renderer
        $renderer = new SmartyRenderer;
        $renderer->setResolver($resolver);

        // Inject helpers
        $this->injectHelpers($renderer, $container);

        // Inject renderer
        $view = new ZendViewRenderer($renderer, isset($config['layout']) ? $config['layout'] : null);

        // Add template paths
        $allPaths = isset($config['paths']) && is_array($config['paths']) ? $config['paths'] : [];
        foreach ($allPaths as $namespace => $paths) {
            $namespace = is_numeric($namespace) ? null : $namespace;
            foreach ((array) $paths as $path) {
                $view->addPath($path, $namespace);
            }
        }

        return $view;
    }
    
    private function injectHelpers(SmartyRenderer $renderer, ContainerInterface $container)
    {
        $helpers = $container->has(HelperPluginManager::class)
            ? $container->get(HelperPluginManager::class)
            : new HelperPluginManager($container);

        $helpers->setAlias('url', BaseUrlHelper::class);
        $helpers->setAlias('Url', BaseUrlHelper::class);
        $helpers->setFactory(BaseUrlHelper::class, function () use ($container) {
            if (! $container->has(BaseUrlHelper::class)) {
                throw new Exception\MissingHelperException(sprintf(
                    'An instance of %s is required in order to create the "url" view helper; not found',
                    BaseUrlHelper::class
                ));
            }
            return new UrlHelper($container->get(BaseUrlHelper::class));
        });

        $helpers->setAlias('serverurl', BaseServerUrlHelper::class);
        $helpers->setAlias('serverUrl', BaseServerUrlHelper::class);
        $helpers->setAlias('ServerUrl', BaseServerUrlHelper::class);
        $helpers->setFactory(BaseServerUrlHelper::class, function () use ($container) {
            if (! $container->has(BaseServerUrlHelper::class)) {
                throw new Exception\MissingHelperException(sprintf(
                    'An instance of %s is required in order to create the "url" view helper; not found',
                    BaseServerUrlHelper::class
                ));
            }
            return new ServerUrlHelper($container->get(BaseServerUrlHelper::class));
        });

        $renderer->setHelperPluginManager($helpers);
    }
}
