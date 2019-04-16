<?php
/**
 * Server module for HiPanel
 *
 * @link      https://gitorder.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2018, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\stock\menus;

use hiqdev\yii2\menus\Menu;
use Yii;

class OrderActionsMenu extends Menu
{
    public $model;

    public function items()
    {
        return [
            'view' => [
                'label' => Yii::t('hipanel', 'View'),
                'icon' => 'fa-info',
                'url' => ['@order/view', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('order.read'),
            ],
            'update' => [
                'label' => Yii::t('hipanel', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@order/update', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('order.update'),
            ],
        ];
    }
}
