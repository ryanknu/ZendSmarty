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
}