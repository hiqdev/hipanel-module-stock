<?php

/*
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\stock\menus;

use Yii;

class SidebarMenu extends \hiqdev\yii2\menus\Menu
{
    public function items()
    {
        $user = Yii::$app->user;
        return [
            'stock' => [
                'label'   => Yii::t('hipanel:stock', 'Stock'),
                'url'     => ['/stock/model/index'],
                'icon'    => 'fa-cubes',
                'visible' => $user->can('model.read') || $user->can('part.read') || $user->can('move.read'),
                'items' => [
                    'model' => [
                        'label' => Yii::t('hipanel:stock', 'Models'),
                        'url'   => ['/stock/model/index'],
                        'icon'  => 'fa-cube',
                        'visible' => $user->can('model.read'),
                    ],
                    'part' => [
                        'label' => Yii::t('hipanel:stock', 'Parts'),
                        'url'   => ['/stock/part/index'],
                        'icon'  => 'fa-cubes',
                        'visible' => $user->can('part.read'),
                    ],
                    'move' => [
                        'label' => Yii::t('hipanel:stock', 'History'),
                        'url'   => ['/stock/move/index'],
                        'icon'  => 'fa-history',
                        'visible' => $user->can('move.read'),
                    ],
                    'order' => [
                        'label' => Yii::t('hipanel.stock.order', 'Orders'),
                        'url'   => ['@order/index'],
                        'icon'  => 'fa-shopping-basket',
                        'visible' => $user->can('order.read'),
                    ],
                    'model-group' => [
                        'label' => Yii::t('hipanel:stock', 'Model groups'),
                        'url'   => ['/stock/model-group/index'],
                        'icon'  => 'fa-folder-open',
                        'visible' => $user->can('model.read'),
                    ],
                    'hwconfig' => [
                        'label'   => Yii::t('hipanel:stock', 'Config Templates'),
                        'url'     => ['/stock/hwconfig'],
                        'visible' => false,
                    ],
                    'mobile-manager' => [
                        'label'   => Yii::t('hipanel:stock', 'Mobile manager'),
                        'url'     => ['/stock/mobile/index'],
                        'icon'  => 'fa-mobile',
                        'visible' => $user->can('test.beta') && $user->can('move.read'),
                    ],
                ],
            ],
        ];
    }
}
