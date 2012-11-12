# Zend Smarty
================
A Smarty Module for Zend Framework 2.
Designed to *properly* implement Smarty into the Zend Framework, unlike other approaches this one mimics the way ZF 2 works.

## Limitation
=============
You need to change all of your template names in your module.config.php to point at .tpl files. The .tpl extension will trigger the
Smarty view rendering strategy and everything will work as you want. Access to `$this` inside the smarty file is doable via 
`{$renderer}`, however do note that you won't be able to call all functions on it that you can in a .phtml file.

## Instructions
==========
1. Download the source of this, copy ZSmarty to Project/vendor/ZSmarty
2. Add 'ZSmarty' to the module list in config/application.config.php
3. Add "'Smarty' => 'ZSmarty\StrategyFactory'," to your module.config.php file's 'factories' section (make it so it can find the factory)
4. Add "'strategies' => array('Smarty')" to your module.config.php file's 'view_manager' section
5. Add 'use ZSmarty\SmartyModel;' to your controller's source file's use statements
6. Use the SmartyModel the same way you would use a ViewModel object
