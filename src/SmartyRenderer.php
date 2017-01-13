<?php

namespace ZSmarty;
use Smarty;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Model\ViewModel;
use Zend\View\Resolver\TemplatePathStack;
use Zend\View\Resolver\ResolverInterface;

class SmartyRenderer extends PhpRenderer
{
    protected $_smarty;
    private $__resolver;
    
    public function __construct($tmplPath = null, $extraParams = array())
    {
    	if ( isset($extraParams['smarty']) && $extraParams['smarty'] instanceof Smarty ) {
			$this->_smarty = $extraParams['smarty'];
    	}
        else if ( class_exists('Smarty') ) {
            $this->_smarty = new Smarty;
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
    
    /**
     * Retrieve template name or template resolver
     *
     * @param  null|string $name
     * @return string|Resolver
     */
    public function resolver($name = null)
    {
        if (null === $this->__resolver) {
            $this->__resolver = new TemplatePathStack;
            $this->__resolver->setDefaultSuffix('tpl');
        }

        if (null !== $name) {
            // var_dump($this->__resolver);
            return $this->__resolver->resolve($name, $this);
        }

        return $this->__resolver;
    }
    
    public function setResolver(ResolverInterface  $resolver)
    {
        $this->__resolver = $resolver;
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
        
        $content = $this->_smarty->fetch($this->file);
        return $content;
    }
}
