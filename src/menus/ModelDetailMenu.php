<?php

namespace hipanel\modules\stock\menus;

use hipanel\menus\AbstractDetailMenu;
use hipanel\modules\stock\widgets\HardwareSettingsButton;
use Yii;

class ModelDetailMenu extends AbstractDetailMenu
{
    public $model;

    public function items()
    {
        return [
            [
                'label' => Yii::t('hipanel:stock', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@model/update', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('model.update') && !$this->model->isDeleted(),
            ],
            [
                'label' => HardwareSettingsButton::widget(['id' => $this->model->id, 'type' => $this->model->type]),
                'encode' => false,
                'visible' => Yii::$app->user->can('model.update'),
            ],
            [
                'label' => Yii::t('hipanel:stock', 'Delete'),
                'icon' => 'fa-trash',
                'url' => ['@model/delete', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('model.delete') && !$this->model->isDeleted(),
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'pjax' => '0',
                        'form' => 'delete',
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
