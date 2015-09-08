<?php

namespace hipanel\modules\stock\grid;

use common\components\Lang;
use hipanel\grid\ActionColumn;
use hipanel\grid\BoxedGridView;
use Yii;

class PartGridView extends BoxedGridView
{
    public static function defaultColumns()
    {
        return [
            'main' => [
                'label' => 'Type / Manufacturer',
                'value' => function($model) {
                    return sprintf('%s %s', Lang::t($model->model_type_label), Lang::t($model->model_brand_label));
                },
            ],
            'partno' => [],
            'serial' => [],
            'last_move' => [
                'filter' => false,
                'format' => 'html',
                'value' => function($model) {
                    return sprintf('%s &nbsp;←&nbsp; %s', $model->dst_name, $model->src_name);
                },
            ],
            'move_type' => [
                'filter' => false,
                'format' => 'html',
                'value' => function($model) {
                    return sprintf('%s &nbsp;←&nbsp; %s', $model->dst_name, $model->src_name);
                },
            ],
            'move_time' => [
                'filter' => false,
            ],
            'order_data' => [
                'filter' => false,
            ],
            'DC_ticket_ID' => [
                'filter' => false,
                'value' => function() {
                    return '1';
                }
            ],
            'actions' => [
                'class' => ActionColumn::className(),
                'template' => '{view} {update}',
                'header' => Yii::t('app', 'Actions'),
            ],
        ];
    }
}