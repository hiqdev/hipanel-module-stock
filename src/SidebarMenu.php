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

use Yii;

class SidebarMenu extends \hiqdev\menumanager\Menu
{
    protected $_addTo = 'sidebar';

    protected $_where = [
        'after'  => ['hosting', 'servers', 'domains', 'tickets', 'finance', 'clients', 'dashboard'],
    ];

    protected $_items = [
        'stock' => [
            'label' => 'Stock',
            'url'   => ['/stock/model/index'],
            'icon'  => 'fa-cubes',
            'items' => [
                'model' => [
                    'label' => 'Models',
                    'url'   => ['/stock/model'],
                ],
                'part' => [
                    'label' => 'Parts',
                    'url'   => ['/stock/part'],
                ],
                'move' => [
                    'label' => 'History',
                    'url'   => ['/stock/move'],
                ],
                'hwconfig' => [
                    'label' => 'Config Templates',
                    'url'   => ['/stock/hwconfig'],
                ],
            ],
        ],
    ];

    public function init()
    {
        parent::init();
        /// XXX quick fix to be redone with 'visible'
        if (!Yii::$app->user->can('support')) {
            unset($this->_items['stock']);
        }
    }
}
