<?php

namespace hipanel\modules\stock\menus;

use Yii;

class ModelGroupDetailMenu extends \hipanel\menus\AbstractDetailMenu
{
    public $model;

    public function items()
    {
        return [
            [
                'label' => Yii::t('hipanel:stock', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@model-group/update', 'id' => $this->model->id],
            ],
            [
                'label' => Yii::t('hipanel:stock', 'Copy'),
                'icon' => 'fa-copy',
                'url' => ['@model-group/copy', 'id' => $this->model->id],
            ],
            [
                'label' => Yii::t('hipanel:stock', 'Delete'),
                'icon' => 'fa-trash',
                'url' => ['@model-group/delete', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('model.delete'),
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'pjax' => '0',
                        'form' => 'approve-transfer',
                        'confirm' => Yii::t('hipanel:stock', 'Are you sure you want to delete this model?'),
                        'params' => [
                            'ModelGroup[id]' => $this->model->id,
                        ],
                    ],
                ],
            ],
        ];
    }
}
