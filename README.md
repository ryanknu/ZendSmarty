# Zend Smarty
================

A Smarty Module for Zend Framework 2. **As of 0.3, it is now compatible with Zend Expressive as well, but your package must will require `zendframework/zend-expressive-zendviewrenderer`, or that you select `Zend View Manager` as your renderer during the Zend Expressive quick set up.**.

This package is designed to *properly* implement Smarty into the Zend Framework, unlike other approaches this one mimics the way ZF 2 works. It creates View Model, Strategy, and Renderers and provides factories to them.

## Limitation
=============

You need to change all of your template names in your module.config.php to point at .tpl files. The .tpl extension will trigger the
Smarty view rendering strategy and everything will work as you want. Access to `$this` inside the smarty file is doable via 
`{$renderer}`. For example, `<a href="{$renderer->url('object/edit', ['id' => 1234])}">`.

Regular Smarty limitations apply, which makes it not always easy to change a phtml file to a tpl file. You can pass functions in and call them via `{call_user_func...}`, and you can pass in arrays but they must use square bracket syntax `{if in_array(4, [1,2,3,4])}`.

## Instructions
==========

1. Get this package. You can get this package through composer now. Running `composer require ryanknu/zendsmarty` will download this package.
2. Get Smarty. I do not bundle it as a dependency because I want to be compatible with any Smarty you want. Typing `composer require smarty/smarty` will get you the latest version.
3. Set your strategies in your project configuration to point to ZSmarty. This will vary a little from project to project, but this is the best gist I can give oyu. This is as easy as setting the following line in a Zend Expressive configuration. (ZF 2.0 and older follow instructions below):
```
    Zend\Expressive\Template\TemplateRendererInterface::class =>
        ZSmarty\Factory\StrategyFactory::class,
```

## Instructions (OLD STYLE)
==========

1. Download the source of this, copy ZSmarty to Project/vendor/ZSmarty
2. Add 'ZSmarty' to the module list in config/application.config.php
3. Add "'Smarty' => 'ZSmarty\StrategyFactory'," to your module.config.php file's 'factories' section (make it so it can find the factory)
4. Add "'strategies' => array('Smarty')" to your module.config.php file's 'view_manager' section
5. Add 'use ZSmarty\SmartyModel;' to your controller's source file's use statements
6. Use the SmartyModel the same way you would use a ViewModel object
