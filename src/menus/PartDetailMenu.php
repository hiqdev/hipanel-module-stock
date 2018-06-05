<?php

namespace hipanel\modules\stock\menus;

use hipanel\widgets\SettingsModal;
use Yii;

class PartDetailMenu extends \hipanel\menus\AbstractDetailMenu
{
    public $model;

    public function items()
    {
        return [
            'copy' => [
                'label' => SettingsModal::widget([
                    'model' => $this->model,
                    'title' => Yii::t('hipanel:stock', 'Sell part'),
                    'labelTemplate' => '<span class="pull-right">{icon}</span>&nbsp;{label}',
                    'icon' => 'fa-long-arrow-right fa-fw',
                    'scenario' => 'buyout',
                    'handleSubmit' => false,
                ]),
                'encode' => false,
            ],
            [
                'label' => $this->model->reserve ? Yii::t('hipanel:stock', 'Unreserve') : Yii::t('hipanel:stock', 'Reserve'),
                'icon' => 'fa-history',
                'url' => $this->model->reserve ? ['@part/unreserve', 'id' => $this->model->id] : [
                    '@part/reserve',
                    'id' => $this->model->id,
                ],
            ],
            [
                'label' => Yii::t('hipanel:stock', 'Replace'),
                'icon' => 'fa-repeat',
                'url' => ['@part/replace', 'id' => $this->model->id],
            ],
            [
                'label' => Yii::t('hipanel:stock', 'Copy'),
                'icon' => 'fa-files-o',
                'url' => ['@part/copy', 'id' => $this->model->id],
            ],
            [
                'label' => Yii::t('hipanel:stock', 'Move'),
                'icon' => 'fa-arrows-h',
                'url' => ['@part/move-by-one', 'id' => $this->model->id],
            ],
            [
                'label' => Yii::t('hipanel', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@part/update', 'id' => $this->model->id],
            ],
            [
                'label' => Yii::t('hipanel:stock', 'Trash'),
                'icon' => 'fa-trash-o',
                'url' => ['@part/trash', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('part.delete'),
            ],
        ];
    }
}
