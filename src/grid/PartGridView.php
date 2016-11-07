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
use hipanel\grid\CurrencyColumn;
use hipanel\grid\DataColumn;
use hipanel\grid\MainColumn;
use hipanel\grid\RefColumn;
use hipanel\modules\stock\models\Move;
use hipanel\modules\stock\models\Part;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use Yii;
use yii\helpers\Html;

class PartGridView extends BoxedGridView
{
    protected static $_locations;

    public function setLocations($value)
    {
        self::$_locations = $value;
    }

    public static function getLocations()
    {
        return self::$_locations;
    }

    public static function defaultColumns()
    {
        return [
            'serial' => [
                'class' => MainColumn::class,
                'filterAttribute' => 'serial_like',
                'format' => 'html',
            ],
            'main' => [
                'label' => Yii::t('hipanel', 'Type') . ' / ' . Yii::t('hipanel:stock', 'Manufacturer'),
                'sortAttribute' => 'model_type',
                'value' => function ($model) {
                    return $model->model_type_label . ' ' . $model->model_brand_label;
                },
            ],
            'partno' => [
                'class' => DataColumn::class,
                'filterAttribute' => 'partno_like',
                'filter' => function ($column, $model, $attribute) {
                    return PartnoCombo::widget([
                        'model' => $model,
                        'attribute' => $attribute,
                        'formElementSelector' => 'td',
                    ]);
                },
            ],
            'model' => [
                'attribute' => 'model',
                'format' => 'raw',
                'label' => Yii::t('hipanel:stock', 'Model'),
                'value' => function ($model) {
                    return Html::a($model->model->model, ['@model/view', 'id' => $model->model_id]);
                },
            ],
            'model_type' => [
                'class' => RefColumn::class,
                'gtype' => 'type,model',
                'value' => function ($model) {
                    return $model->model_type_label;
                },
            ],
            'model_brand' => [
                'class' => RefColumn::class,
                'gtype' => 'type,brand',
                'value' => function ($model) {
                    return $model->model_brand_label;
                },
            ],
            'last_move' => [
                'label' => Yii::t('hipanel:stock', 'Last move'),
                'sortAttribute' => 'dst_name',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::tag('b', $model->dst_name) . '&nbsp;←&nbsp;' . $model->src_name;
                },
            ],
            'move_type_and_date' => [
                'label' => Yii::t('hipanel', 'Type') . ' / ' . Yii::t('hipanel', 'Date'),
                'sortAttribute' => 'move_time',
                'format' => 'raw',
                'value' => function ($model) {
                    $linkToMove = Html::a($model->move_type_label, ['@move/index', 'MoveSearch' => ['serial_like' => $model->serial]], ['class' => 'text-bold']);
                    return $linkToMove . '<br>' . Html::tag('nobr', Yii::$app->formatter->asDate($model->move_time));
                },
            ],
            'move_type_label' => [
                'filter' => false,
                'enableSorting' => false,
                'format' => 'html',
                'value' => function ($model) {
                    return $model->move_type_label;
                },
            ],
            'move_time' => [
                'filter' => false,
                'format' => 'datetime',
            ],
            'move_date' => [
                'attribute' => 'move_time',
                'filter' => false,
                'format' => 'date',
                'sortAttribute' => 'move_time',
            ],
            'create_time' => [
                'filter' => false,
                'format' => 'datetime',
            ],
            'create_date' => [
                'attribute' => 'create_time',
                'filter' => false,
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::tag('nobr', Yii::$app->formatter->asDate($model->create_time));
                },
            ],
            'order_data' => [
                'filter' => false,
                'enableSorting' => false,
            ],
            'dc_ticket' => [
                'filter' => false,
                'enableSorting' => false,
                'value' => function ($model) {
                    $out = '';
                    if ($model['move_remote_ticket']) {
                        $out .= $model['move_remote_ticket'] . '<br>';
                    }
                    if ($model['move_hm_ticket']) {
                        $out .= $model['move_hm_ticket'] . '<br>';
                    }
                    if ($model['move_remotehands_label']) {
                        $out .= $model['move_remotehands_label'] . '<br>';
                    }
                    return $out;
                },
            ],
            'price' => [
                'class' => CurrencyColumn::class,
                'filterAttribute' => 'currency',
                'filter' => function ($column, $model, $attribute) {
                    $values = ['usd' => 'USD', 'eur' => 'EUR'];
                    return Html::activeDropDownList($model, 'currency', $values, [
                        'class' => 'form-control',
                        'prompt' => Yii::t('hipanel', '----------'),
                    ]);
                },
            ],
            'place' => [
                'filter' => function ($column, $model, $attribute) {
                    return Html::activeDropDownList($model, 'place', self::getLocations(), [
                        'class' => 'form-control',
                        'prompt' => Yii::t('hipanel', '----------'),
                    ]);
                },
            ],
            'actions' => [
                'class' => ActionColumn::class,
                'template' => '{view} {update}',
                'header' => Yii::t('hipanel', 'Actions'),
            ],
            'move_descr' => [
                'format' => 'html',
                'value' => function ($model) {
                    return Move::prepareDescr($model->move_descr);
                },
            ],
        ];
    }

    public static function defaultRepresentations()
    {
        return [
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => [
                    'checkbox',
                    'model_type', 'model_brand', 'partno', 'serial',
                    'last_move', 'move_type_and_date', 'move_descr',
                ],
            ],
            'report' => [
                'label' => Yii::t('hipanel', 'report'),
                'columns' => [
                    'checkbox',
                    'model_type', 'model_brand', 'partno', 'serial',
                    'create_date', 'price', 'place',
                ],
            ],
            'detailed' => Yii::$app->user->can('tmp disabled') ? [
                'label' => Yii::t('hipanel', 'detailed'),
                'columns' => [
                    'checkbox',
                    'model_type', 'model_brand',
                    'partno', 'serial',
                    'last_move', 'move_type_and_date',
                    'move_descr', 'order_data', 'dc_ticket',
                ],
            ] : '',
        ];
    }
}
