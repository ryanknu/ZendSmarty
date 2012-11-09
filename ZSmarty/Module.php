<?php

namespace ZSmarty;

class Module
{
	public function __construct()
	{
		include __DIR__ . '/libs/Smarty.class.php';
		
		//$x = new SmartyStrategy(new SmartyRenderer);
		//print_r($x);
	}
	
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }
}