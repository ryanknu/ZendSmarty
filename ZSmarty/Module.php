<?php

namespace ZSmarty;

class Module
{
	public function __construct()
	{
		if ( !class_exists('Smarty') )
		{
			$file = stream_resolve_include_path('Smarty.class.php');
			
			if ( !$file )
			{
				$o_file = __DIR__ . '/libs/Smarty.class.php';
				if ( file_exists( $o_file ) )
				{
					$file = $o_file;
				}
			}
			
			if ( !$file )
			{
				throw new \Exception("Could not locate Smarty (In include path OR $o_file)");
			}
			
			require $file;
		}
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