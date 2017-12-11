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
        return [
            'stock' => [
                'label'   => Yii::t('hipanel:stock', 'Stock'),
                'url'     => ['/stock/model/index'],
                'icon'    => 'fa-cubes',
                'visible' => Yii::$app->user->can('stock.read'),
                'items' => [
                    'model' => [
                        'label' => Yii::t('hipanel:stock', 'Models'),
                        'url'   => ['/stock/model/index'],
                    ],
                    'part' => [
                        'label' => Yii::t('hipanel:stock', 'Parts'),
                        'url'   => ['/stock/part/index'],
                    ],
                    'move' => [
                        'label' => Yii::t('hipanel:stock', 'History'),
                        'url'   => ['/stock/move/index'],
                    ],
                    'hwconfig' => [
                        'label'   => Yii::t('hipanel:stock', 'Config Templates'),
                        'url'     => ['/stock/hwconfig'],
                        'visible' => false,
                    ],
                ],
            ],
        ];
    }
}
