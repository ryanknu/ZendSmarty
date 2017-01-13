<?php

namespace ZSmarty;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\View\ViewEvent;

class SmartyStrategy implements ListenerAggregateInterface
{
    protected $listeners = array();
    protected $renderer;
    protected $layouts;

    public function __construct(SmartyRenderer $renderer, $layouts)
    {
        $this->renderer = $renderer;
        $this->layouts = $layouts;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RENDERER, array($this, 'selectRenderer'), $priority);
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RESPONSE, array($this, 'injectResponse'), $priority);
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }
    
    public function selectRenderer(ViewEvent $e)
    {
    	$model = $e->getModel();
    	$name = $model->getTemplate();
    	if ( array_key_exists($name, $this->layouts) )
    	{
	    	$name = $this->layouts[$name];
    	}
    	
    	$last4 = substr($name, strlen($name) - 4);
    	
    	if ( $last4 == '.tpl' )
    	{
	    	return $this->renderer;
    	}
    	
        return null;
    }

    public function injectResponse(ViewEvent $e)
    {
        $renderer = $e->getRenderer();
        if ($renderer !== $this->renderer) {
            return;
        }

        $result   = $e->getResult();
        if (!is_string($result)) {
            return;
        }

        $response = $e->getResponse();
        $response->setContent($result);
    }
}
