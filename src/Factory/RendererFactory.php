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
use ZSmarty\SmartyRenderer;

class RendererFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $smarty = $serviceLocator->get('ZSmarty\Engine');
        $resolver = $serviceLocator->get('ViewResolver');
        
        $sr = new SmartyRenderer(null, array('smarty' => $smarty));
    
        // Switch SM-generated TemplatePathStack to use .tpl for extension
        foreach($resolver->getIterator() as $listener)
        {
            if ( $listener instanceof TemplatePathStack )
            {
                $listener->setDefaultSuffix('tpl');
            }
        }
    
        $sr->setResolver($resolver);
        $sr->setHelperPluginManager($serviceLocator->get('ViewHelperManager'));
        return $sr;
    }
}