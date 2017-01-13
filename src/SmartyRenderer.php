<?php

namespace ZSmarty;
use Smarty;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Model\ViewModel;

class SmartyRenderer extends PhpRenderer
{
    protected $_smarty;
    
    public function __construct($tmplPath = null, $extraParams = array())
    {
    	if ( isset($extraParams['smarty']) && $extraParams['smarty'] instanceof Smarty ) {
			$this->_smarty = $extraParams['smarty'];
    	}
    	else {
    		throw new \Exception('Can not instantiate SmartyRenderer without Smarty engine');
    	}

        if (null !== $tmplPath) {
            $this->setScriptPath($tmplPath);
        }

        foreach ($extraParams as $key => $value) {
            $this->_smarty->$key = $value;
        }
    }

    public function getEngine()
    {
        return $this->_smarty;
    }

    public function setScriptPath($path)
    {
        if (is_readable($path)) {
            $this->_smarty->template_dir = $path;
            return;
        }

        throw new \Exception('Invalid path provided');
    }

    public function getScriptPaths()
    {
        return array($this->_smarty->template_dir);
    }

    public function __set($key, $val)
    {
        $this->_smarty->assign($key, $val);
    }

    public function __isset($key)
    {
        return null !== $this->_smarty->getTemplateVars($key);
    }
    
    public function __get($key)
    {
        return $this->_smarty->getTemplateVars($key);
    }

    public function __unset($key)
    {
        $this->_smarty->clear_assign($key);
    }

    public function assign($spec, $value = null)
    {
        if (is_array($spec)) {
            $this->_smarty->assign($spec);
            return;
        }

        $this->_smarty->assign($spec, $value);
    }

    public function clearVars()
    {
        $this->_smarty->clear_all_assign();
    }

    public function render($name, $vars=null)
    {
    	if ( $name instanceof ViewModel )
    	{
	    	$model = $name;
            $name = $model->getTemplate();
            if (empty($name)) {
                throw new Exception\DomainException(sprintf(
                    '%s: received View Model argument, but template is empty',
                    __METHOD__
                ));
            }

            // Give view model awareness via ViewModel helper
            $helper = $this->plugin('view_model');
            $helper->setCurrent($model);

            foreach($model->getVariables() as $key => $value)
            {
	            $this->assign($key, $value);
            }
            $this->assign('renderer', $this);
            
            unset($model);
            
            $this->file = $this->resolver($name);
            
            return $this->getFilterChain()->filter($this->_smarty->fetch($this->file));
    	}
    	else
    	{
	    	if (null !== $vars)
	    	{
	            foreach($vars as $k=>$v)
	            {
	                $this->assign($k,$v);
	            }
	        }
	        $this->file = $this->resolver($name);
	        $this->this = $this;
	        return $this->getFilterChain()->filter($this->_smarty->fetch($this->file));
    	}
    }
}
