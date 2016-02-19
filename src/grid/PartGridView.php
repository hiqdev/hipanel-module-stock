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
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use Yii;

class PartGridView extends BoxedGridView
{
    public static function defaultColumns()
    {
        return [
            'main'              => [
                'label'             => Yii::t('app', 'Type') . ' / ' . Yii::t('app', 'Manufacturer'),
                'value'             => function ($model) {
                    return $model->model_type_label . ' ' . $model->model_brand_label;
                },
            ],
            'partno'            => [
                'class'             => \hipanel\grid\DataColumn::className(),
                'filterAttribute'   => 'partno_like',
                'filter'            => function ($column, $model, $attribute) {
                    return PartnoCombo::widget([
                        'model'               => $model,
                        'attribute'           => $attribute,
                        'formElementSelector' => 'td',
                    ]);
                },
            ],
            'serial'            => [
                'filterAttribute'   => 'serial_like',
            ],
            'last_move'         => [
                'label'             => Yii::t('app', 'Last move'),
                'filter'            => false,
                'format'            => 'html',
                'value'             => function ($model) {
                    return Yii::t('app', '{0} &nbsp;←&nbsp; {1}', [$model->dst_name, $model->src_name]);
                },
            ],
            'move_type_label'   => [
                'filter'            => false,
                'enableSorting'     => false,
                'format'            => 'html',
                'value'             => function ($model) {
                    return $model->move_type_label;
                },
            ],
            'move_time'         => [
                'filter'            => false,
                'format'            => 'datetime',
                'sortAttribute'     => 'time',
            ],
            'move_date'         => [
                'attribute'         => 'move_time',
                'filter'            => false,
                'format'            => 'date',
                'sortAttribute'     => 'time',
            ],
            'order_data'        => [
                'filter'            => false,
                'enableSorting'     => false,
            ],
            'DC_ticket_ID'      => [
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
            'actions'           => [
                'class'             => ActionColumn::className(),
                'template'          => '{view} {update}',
                'header'            => Yii::t('app', 'Actions'),
            ],
        ];
    }
}
