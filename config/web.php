<?php

/*
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

/**
 * @var array $params
 */

return [
    'aliases' => [
        '@model'          => '/stock/model',
        '@model-group'    => '/stock/model-group',
        '@part'           => '/stock/part',
        '@move'           => '/stock/move',
        '@hwconfig'       => '/stock/hwconfig',
        '@order'          => '/stock/order',
    ],
    'modules' => [
        'stock' => [
            'class' => \hipanel\modules\stock\Module::class,
        ],
    ],
    'components' => [
        'i18n' => [
            'translations' => [
                'hipanel:stock' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => dirname(__DIR__) . '/src/messages',
                ],
                'hipanel.stock.order' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => dirname(__DIR__) . '/src/messages',
                ],
            ],
        ],
    ],
    'container' => [
        'singletons' => [
            \hipanel\modules\stock\Module::class => [
                'stocksList' => $params['module.stock.stocks_list'] ?? [],
            ],
        ],
        'definitions' => [
            \hipanel\modules\dashboard\menus\DashboardMenu::class => [
                'add' => [
                    'stock' => [
                        'menu' => [
                            'class' => \hipanel\modules\stock\menus\DashboardItem::class,
                        ],
                        'where' => [
                            'after'  => ['hosting', 'servers', 'domains', 'tickets', 'finance', 'clients', 'dashboard'],
                        ],
                    ],
                ],
            ],
            \hiqdev\thememanager\menus\AbstractSidebarMenu::class => [
                'add' => [
                    'stock' => [
                        'menu' => \hipanel\modules\stock\menus\SidebarMenu::class,
                        'where' => [
                            'after'  => ['hosting', 'servers', 'domains', 'tickets', 'finance', 'clients', 'dashboard'],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
