<?php

namespace hipanel\modules\stock\menus;

use hiqdev\yii2\menus\Menu;
use Yii;

class OrderActionsMenu extends Menu
{
    public $model;

    public function items(): array
    {
        return [
            'view' => [
                'label' => Yii::t('hipanel', 'View'),
                'icon' => 'fa-info',
                'url' => ['@order/view', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('order.read'),
            ],
            [
                'label' => Yii::t('hipanel.stock.order', 'Profit report'),
                'icon' => 'fa-bar-chart',
                'url' => ['@order/profit-view', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('order.read-profits'),
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
