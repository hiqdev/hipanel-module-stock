<?php

namespace hipanel\modules\stock\menus;

use Yii;

class ModelDetailMenu extends \hipanel\menus\AbstractDetailMenu
{
    public $model;

    public function items()
    {
        return [
            [
                'label' => Yii::t('hipanel:stock', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@model/update', 'id' => $this->model->id],
            ],
            [
                'label' => Yii::t('hipanel:stock', 'Delete'),
                'icon' => 'fa-trash',
                'url' => ['@model/update', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('model.delete'),
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'pjax' => '0',
                        'form' => 'approve-transfer',
                        'confirm' => Yii::t('hipanel:stock', 'Are you sure you want to delete this model?'),
                        'params' => [
                            'Model[id]' => $this->model->id,
                        ],
                    ],
                ],
            ],
        ];
    }
}
