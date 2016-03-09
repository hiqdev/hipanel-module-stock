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
use hipanel\grid\CurrencyColumn;
use hipanel\grid\BoxedGridView;
use hipanel\grid\RefColumn;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hipanel\grid\DataColumn;
use Yii;
use yii\helpers\Html;

class PartGridView extends BoxedGridView
{
    protected static $_locations;

    public function setLocations($value) {
        self::$_locations = $value;
    }

    public static function getLocations() {
        return self::$_locations;
    }

    public static function defaultColumns()
    {
        return [
            'main' => [
                'label'             => Yii::t('app', 'Type') . ' / ' . Yii::t('app', 'Manufacturer'),
                'value'             => function ($model) {
                    return $model->model_type_label . ' ' . $model->model_brand_label;
                },
            ],
            'partno' => [
                'class'             => DataColumn::class,
                'filterAttribute'   => 'partno_like',
                'filter'            => function ($column, $model, $attribute) {
                    return PartnoCombo::widget([
                        'model'               => $model,
                        'attribute'           => $attribute,
                        'formElementSelector' => 'td',
                    ]);
                },
            ],
            'model_type' => [
                'class'  => RefColumn::className(),
                'gtype'  => 'type,model',
                'value'  => function ($model) {
                    return $model->model_type_label;
                },
            ],
            'model_brand' => [
                'class'  => RefColumn::className(),
                'gtype'  => 'type,brand',
                'value'  => function ($model) {
                    return $model->model_brand_label;
                },
            ],
            'serial' => [
                'filterAttribute'   => 'serial_like',
            ],
            'last_move' => [
                'label'             => Yii::t('app', 'Last move'),
                'filter'            => false,
                'format'            => 'html',
                'value'             => function ($model) {
                    return Yii::t('app', '{0} &nbsp;←&nbsp; {1}', [$model->dst_name, $model->src_name]);
                },
            ],
            'move_type_label' => [
                'filter'            => false,
                'enableSorting'     => false,
                'format'            => 'html',
                'value'             => function ($model) {
                    return $model->move_type_label;
                },
            ],
            'move_time' => [
                'filter'            => false,
                'format'            => 'datetime',
            ],
            'move_date' => [
                'attribute'         => 'move_time',
                'filter'            => false,
                'format'            => 'date',
                'sortAttribute'     => 'move_time',
            ],
            'create_time' => [
                'filter'            => false,
                'format'            => 'datetime',
            ],
            'create_date' => [
                'attribute'         => 'create_time',
                'filter'            => false,
                'format'            => 'date',
            ],
            'order_data' => [
                'filter'            => false,
                'enableSorting'     => false,
            ],
            'DC_ticket_ID' => [
                'label'             => Yii::t('app', 'DC ticket ID'),
                'filter'            => false,
                'enableSorting'     => false,
                'value'             => function ($model) {
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
                'class'             => CurrencyColumn::class,
                'filterAttribute'   => 'currency',
                'filter'            => function ($column, $model, $attribute) {
                    $values = ['usd' => 'USD', 'eur' => 'EUR'];
                    return Html::activeDropDownList($model, 'currency', $values, [
                        'class'     => 'form-control',
                        'prompt'    => Yii::t('app', '----------'),
                    ]);
                },
            ],
            'place' => [
                'filter'            => function ($column, $model, $attribute) {
                    return Html::activeDropDownList($model, 'place', self::getLocations(), [
                        'class'     => 'form-control',
                        'prompt'    => Yii::t('app', '----------'),
                    ]);
                },
            ],
            'actions' => [
                'class'             => ActionColumn::class,
                'template'          => '{view} {update}',
                'header'            => Yii::t('app', 'Actions'),
            ],
        ];
    }
}
