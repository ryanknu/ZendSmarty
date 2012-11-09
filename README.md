# Zend Smarty
================
A Smarty Module for Zend Framework 2.
Designed to *properly* implement Smarty into the Zend Framework, unlike other approaches this one mimics the way ZF 2 works.

## Limitation
=============
It is important to note that I could not find a way to remove the ".phtml" from the automatic template name chooser
(example: /view/application/index/index.php) which sucks. To use Smarty, you'll have to call setTemplate on each
SmartyModel object you create. Sorry! If you choose all default behavior it will use the PhpRenderer on a .phtml file.

## Instructions
==========
1. Download the source of this, copy ZSmarty to Project/vendor/ZSmarty
2. Add 'ZSmarty' to the module list in config/application.config.php
3. Add "'Smarty' => 'ZSmarty\StrategyFactory'," to your module.config.php file's 'factories' section (make it so it can find the factory)
4. Add "'strategies' => array('Smarty')" to your module.config.php file's 'view_manager' section
5. Add 'use ZSmarty\SmartyModel;' to your controller's source file's use statements
6. Use the SmartyModel the same way you would use a ViewModel object
