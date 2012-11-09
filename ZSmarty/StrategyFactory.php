<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Mvc
 */

namespace ZSmarty;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Resolver\TemplatePathStack;

/**
 * @category   Zend
 * @package    Zend_Mvc
 * @subpackage Service
 */
class StrategyFactory implements FactoryInterface
{
    /**
     * Create and return the JSON view strategy
     *
     * Retrieves the ViewJsonRenderer service from the service locator, and
     * injects it into the constructor for the JSON strategy.
     *
     * It then attaches the strategy to the View service, at a priority of 100.
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return JsonStrategy
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	$sr = new SmartyRenderer;
    	$resolver = $serviceLocator->get('ViewResolver');
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
        return new SmartyStrategy($sr, $layouts);
    }
}