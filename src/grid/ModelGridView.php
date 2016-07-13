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
use Yii;
use yii\helpers\Html;

class ModelGridView extends BoxedGridView
{
    public static function defaultColumns()
    {
        return [
            'type' => [
                'class' => RefColumn::className(),
                'gtype' => 'type,model',
            ],
            'brand' => [
                'class' => RefColumn::className(),
                'gtype' => 'type,brand',
            ],
            'model' => [
                'filterAttribute' => 'model_like',
            ],
            'partno' => [
                'class' => MainColumn::class,
                'enableSorting' => false,
                'filterAttribute' => 'partno_like',
            ],
            'descr' => [
                'enableSorting' => false,
                'filterAttribute' => 'descr_like',
            ],
            'dtg' => [
                'enableSorting' => false,
                'filter' => false,
                'format' => 'raw',
                'value' => function ($model) {
                    // $out .= Html::tag('span', sprintf('%s: %s', $k, $v), ['class' => 'btn btn-info btn-xs']) . '&nbsp;';
                    return $model->getDcs('dtg');
                },
            ],
            'sdg' => [
                'enableSorting' => false,
                'filter' => false,
                'format' => 'raw',
                'value' => function ($model) {
                    // $out .= Html::tag('span', sprintf('%s: %s', $k, $v), ['class' => 'btn btn-info btn-xs']) . '&nbsp;';
                    return $model->getDcs('sdg');
                },
            ],
            'm3' => [
                'enableSorting' => false,
                'filter' => false,
                'format' => 'raw',
                'value' => function ($model) {
                    // $out .= Html::tag('span', sprintf('%s: %s', $k, $v), ['class' => 'btn btn-info btn-xs']) . '&nbsp;';
                    return $model->getDcs('m3');
                },
            ],
            'last_prices' => [
                'label' => Yii::t('hipanel/stock', 'Last price'),
                'enableSorting' => false,
                'filter' => false,
                'value' => function ($model) {
                    return $model->showModelPrices($model->last_prices);
                },
            ],

            'actions' => [
                'class' => ActionColumn::className(),
                'template' => '{view} {update}',
                'header' => Yii::t('hipanel', 'Actions'),
            ],
        ];
    }
}
