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
use Yii;

class MoveGridView extends BoxedGridView
{
    public static function defaultColumns()
    {
        return [
            'date' => [
                'attribute' => 'time',
                'filter'    => false,
                'format'    => 'date',
                'contentOptions' => ['style' => 'white-space:nowrap'],
            ],
            'time' => [
                'filter'    => false,
                'format'    => 'datetime',
                'contentOptions' => ['style' => 'white-space:nowrap'],
            ],
            'move' => [
                'label' => Yii::t('hipanel/stock', 'Move'),
                'format' => 'html',
                'enableSorting' => false,
                'filter' => false,
                'value' => function ($model) {
                    return sprintf('<b>%s</b>&nbsp;←&nbsp;%s', $model->dst_name, $model->src_name);
                },
            ],
            'descr' => [
                'format' => 'html',
                'enableSorting' => false,
                'filter' => false,
                'value' => function ($model) {
                    return sprintf('<b>%s</b><br>%s', $model->type_label, $model->getDescription());
                },
            ],
            'data' => [
                'enableSorting' => false,
                'filter' => false,
            ],
            'parts' => [
                'enableSorting' => false,
                'filter' => false,
                'value' => function ($model) {
                    $out = '';
                    if (is_array($model->parts)) {
                        foreach ($model->parts as $part) {
                            $out .= $part['partno'] . ':' . $part['serial'] . ' ';
                        }
                    }
                    return $out;
                },
            ],
        ];
    }
}
