<?php

/*
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015, HiQDev (https://hiqdev.com/)
 */

namespace hipanel\modules\stock;

class Plugin extends \hiqdev\pluginmanager\Plugin
{
    protected $_items = [
        'aliases' => [
            '@model'    => '/stock/model',
            '@part'     => '/stock/part',
            '@move'     => '/stock/move',
            '@hwconfig' => '/stock/hwconfig',
        ],
        'menus' => [
            'hipanel\modules\stock\SidebarMenu',
        ],
        'modules' => [
            'stock' => [
                'class' => 'hipanel\modules\stock\Module',
            ],
        ],
    ];
}
