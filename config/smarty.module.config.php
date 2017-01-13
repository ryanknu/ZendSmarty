<?php

return array(
    'factories' => array(
        'ZSmarty\Strategy' => 'ZSmarty\Factory\StrategyFactory',
        'ZSmarty\SmartyRenderer' => 'ZSmarty\Factory\RendererFactory',
        'ZSmarty\Engine' => 'ZSmarty\Factory\SmartyFactory',
    ),
);
