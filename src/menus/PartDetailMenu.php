<?php

namespace hipanel\modules\stock\menus;

use hipanel\modules\stock\models\Part;
use hipanel\widgets\AuditButton;
use hipanel\widgets\SettingsModal;
use hipanel\widgets\SimpleOperation;
use Yii;

class PartDetailMenu extends \hipanel\menus\AbstractDetailMenu
{
    /**
     * @var Part
     */
    public $model;

    public function items()
    {
        return [
            'sell' => [
                'label' => SettingsModal::widget([
                    'model' => $this->model,
                    'title' => Yii::t('hipanel:stock', 'Sell part'),
                    'labelTemplate' => '<span class="pull-right">{icon}</span>&nbsp;{label}',
                    'icon' => 'fa-long-arrow-right fa-fw',
                    'scenario' => 'sell',
                    'handleSubmit' => false,
                ]),
                'encode' => false,
                'visible' => Yii::$app->user->can('part.sell') && $this->model->isNotDeleted()
            ],
            [
                'label' => $this->model->reserve ? Yii::t('hipanel:stock', 'Unreserve') : Yii::t('hipanel:stock', 'Reserve'),
                'icon' => 'fa-history',
                'url' => $this->model->reserve ? ['@part/unreserve', 'id' => $this->model->id] : [
                    '@part/reserve',
                    'id' => $this->model->id,
                ],
                'visible' => Yii::$app->user->can('part.update') && $this->model->isNotDeleted(),
            ],
            [
                'label' => Yii::t('hipanel:stock', 'Replace'),
                'icon' => 'fa-repeat',
                'url' => ['@part/replace', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('move.create') && $this->model->isNotDeleted(),
            ],
            [
                'label' => Yii::t('hipanel:stock', 'Copy'),
                'icon' => 'fa-files-o',
                'url' => ['@part/copy', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('part.create') && $this->model->isNotDeleted(),
            ],
            [
                'label' => Yii::t('hipanel:stock', 'Move'),
                'icon' => 'fa-arrows-h',
                'url' => ['@part/move-by-one', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('move.create') && $this->model->isNotDeleted(),
            ],
            [
                'label' => Yii::t('hipanel', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@part/update', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('part.update') && $this->model->isNotDeleted(),
            ],
            [
                'label' => Yii::t('hipanel:stock', 'Trash'),
                'icon' => 'fa-trash-o',
                'url' => ['@part/trash', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('move.create') && $this->model->isNotDeleted(),
            ],
            [
                'label' => SimpleOperation::widget([
                    'model' => $this->model,
                    'scenario' => 'delete',
                    'buttonLabel' => '<span class="pull-right"><i class="fa fa-fw fa-trash-o"></i></span>' . Yii::t('hipanel', 'Mark as Deleted'),
                    'buttonClass' => '',
                    'body' => Yii::t('hipanel:client', 'Are you sure you want to mark <b>{title}</b> part as deleted?', ['title' => $this->model->title]),
                    'modalHeaderLabel' => Yii::t('hipanel:stock', 'Confirm part deleting'),
                    'modalHeaderOptions' => ['class' => 'label-danger'],
                    'modalFooterLabel' => Yii::t('hipanel:client', 'Mark as Deleted'),
                    'modalFooterLoading' => Yii::t('hipanel:client', 'Marking as Deleted'),
                    'modalFooterClass' => 'btn btn-danger',
                ]),
                'encode' => false,
                'visible' => Yii::$app->user->can('part.delete') && $this->model->isNotDeleted(),
            ],
            [
                'label' => SimpleOperation::widget([
                    'model' => $this->model,
                    'scenario' => 'erase',
                    'buttonLabel' => '<span class="pull-right"><i class="fa fa-fw fa-trash-o"></i></span>' . Yii::t('hipanel', 'Erase'),
                    'buttonClass' => '',
                    'body' => Yii::t('hipanel:client', 'Are you sure you want to erase the <b>{title}</b> part, including its move history?', ['title' => $this->model->title]),
                    'modalHeaderLabel' => Yii::t('hipanel:stock', 'Confirm part erasing'),
                    'modalHeaderOptions' => ['class' => 'label-danger'],
                    'modalFooterLabel' => Yii::t('hipanel:client', 'Erase part'),
                    'modalFooterLoading' => Yii::t('hipanel:client', 'Erasing part'),
                    'modalFooterClass' => 'btn btn-danger',
                ]),
                'encode' => false,
                'visible' => Yii::$app->user->can('part.erase') && $this->model->isDeletable(),
            ],
            [
                'label' => AuditButton::widget(['model' => $this->model, 'rightIcon' => true]),
                'encode' => false,
            ]
        ];
    }
}
