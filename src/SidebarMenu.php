<?php

/*
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\stock;

use Yii;

class SidebarMenu extends \hiqdev\menumanager\Menu
{
    protected $_addTo = 'sidebar';

    protected $_where = [
        'after'  => ['hosting', 'servers', 'domains', 'tickets', 'finance', 'clients', 'dashboard'],
    ];

    public function items()
    {
        return [
            'stock' => [
                'label'   => Yii::t('app', 'Stock'),
                'url'     => ['/stock/model/index'],
                'icon'    => 'fa-cubes',
                'visible' => function () { return Yii::$app->user->can('support'); },
                'items' => [
                    'model' => [
                        'label' => Yii::t('app', 'Models'),
                        'url'   => ['/stock/model/index'],
                    ],
                    'part' => [
                        'label' => Yii::t('app', 'Parts'),
                        'url'   => ['/stock/part/index'],
                    ],
                    'move' => [
                        'label' => Yii::t('app', 'History'),
                        'url'   => ['/stock/move/index'],
                    ],
                    'hwconfig' => [
                        'label'   => Yii::t('app', 'Config Templates'),
                        'url'     => ['/stock/hwconfig'],
                        'visible' => false,
                    ],
                ],
            ],
        ];
    }
}
