<?php

namespace ZSmarty;

class Module
{
    public function __construct()
    {
        require_once __DIR__ . '/Smarty/Smarty.class.php';
    }

    public function getServiceConfig()
    {
        return require __DIR__ . '/config/smarty.module.config.php';
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
