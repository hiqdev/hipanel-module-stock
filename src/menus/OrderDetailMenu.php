<?php

namespace hipanel\modules\stock\menus;

use hipanel\menus\AbstractDetailMenu;
use Yii;

class OrderDetailMenu extends AbstractDetailMenu
{
    public $model;

    public function items()
    {
        return [
            [
                'label' => Yii::t('hipanel:stock', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@order/update', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('order.update'),
            ],
            [
                'label' => Yii::t('hipanel:stock', 'Delete'),
                'icon' => 'fa-trash',
                'url' => ['@order/delete', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('order.delete'),
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'pjax' => '0',
                        'form' => 'delete',
                        'confirm' => Yii::t('hipanel:stock', 'Are you sure you want to delete this model?'),
                        'params' => [
                            'Order[id]' => $this->model->id,
                        ],
                    ],
                ],
            ],
        ];
    }
}
