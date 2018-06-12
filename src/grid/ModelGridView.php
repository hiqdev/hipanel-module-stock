<?php

/*
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\stock\grid;

use hipanel\grid\ActionColumn;
use hipanel\grid\BoxedGridView;
use hipanel\grid\MainColumn;
use hipanel\grid\RefColumn;
use hipanel\modules\stock\models\Model;
use Yii;
use yii\helpers\Html;

class ModelGridView extends BoxedGridView
{
    public function columns()
    {
        return array_merge(parent::columns(), [
            'type' => [
                'class' => RefColumn::class,
                'gtype' => 'type,model',
                'value' => function ($model) {
                    return $model->type_label;
                },
            ],
            'brand' => [
                'class' => RefColumn::class,
                'gtype' => 'type,brand',
                'value' => function ($model) {
                    return $model->brand_label;
                },
            ],
            'model' => [
                'filterAttribute' => 'model_like',
            ],
            'partno' => [
                'enableSorting' => false,
                'filterAttribute' => 'partno_like',
                'format' => 'raw',
                'value' => function (Model $model) {
                    return Html::a(Html::encode($model->partno), [
                        '@model/view', 'id' => $model->id
                    ], ['class' => 'text-bold']);
                }
            ],
            'descr' => [
                'enableSorting' => false,
                'filterAttribute' => 'descr_like',
            ],
            'dtg' => [
                'enableSorting' => false,
                'filter' => false,
                'format' => 'raw',
                'value' => function (Model $model) {
                    return $model->renderReserves('dtg');
                },
            ],
            'sdg' => [
                'enableSorting' => false,
                'filter' => false,
                'format' => 'raw',
                'value' => function (Model $model) {
                    return $model->renderReserves('sdg');
                },
            ],
            'm3' => [
                'enableSorting' => false,
                'filter' => false,
                'format' => 'raw',
                'value' => function (Model $model) {
                    return $model->renderReserves('m3');
                },
            ],
            'twr' => [
                'enableSorting' => false,
                'filter' => false,
                'format' => 'raw',
                'value' => function (Model $model) {
                    return $model->renderReserves('twr');
                },
            ],
            'last_prices' => [
                'label' => Yii::t('hipanel:stock', 'Last price'),
                'enableSorting' => false,
                'filter' => false,
                'format' => 'html',
                'value' => function (Model $model) {
                    return $model->showModelPrices($model->last_prices);
                },
            ],
            'model_group' => [
                'label' => Yii::t('hipanel:stock', 'Model group'),
                'enableSorting' => false,
                'filter' => false,
                'format' => 'raw',
                'value' => function (Model $model) {
                    return Html::a(Html::encode($model->group), ['@model-group/view', 'id' => $model->group_id]);
                }
            ],
            'actions' => [
                'class' => ActionColumn::class,
                'template' => '{view} {update}',
                'header' => Yii::t('hipanel', 'Actions'),
            ],
        ]);
    }
}
