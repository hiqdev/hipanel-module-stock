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
        'after' => ['dashboard', 'header', 'finance', 'tickets', 'domains', 'servers', 'hosting'],
    ];

    protected $_items = [
        'stock' => [
            'label' => 'Clients',
            'url'   => ['/stock/model/index'],
            'icon'  => 'fa-group',
            'items' => [
                'model' => [
                    'label' => 'Models',
                    'url'   => ['/stock/model/index'],
                ],
                'part' => [
                    'label' => 'Parts',
                    'url'   => ['/stock/contact/index'],
                ],
                'move' => [
                    'label' => 'History',
                    'url'   => ['/stock/contact/index'],
                ],
                'part' => [
                    'label' => 'Config Templates',
                    'url'   => ['/stock/contact/index'],
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
