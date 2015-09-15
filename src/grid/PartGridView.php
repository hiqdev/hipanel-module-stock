<?php

namespace hipanel\modules\stock\grid;

use common\components\Lang;
use hipanel\grid\ActionColumn;
use hipanel\grid\BoxedGridView;
use hipanel\modules\stock\controllers\PartController;
use hipanel\modules\stock\models\Part;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use Yii;

class PartGridView extends BoxedGridView
{
    public static function defaultColumns()
    {
        return [
            'main'              => [
                'label'             => Yii::t('app', 'Type') . ' / ' . Yii::t('app', 'Manufacturer'),
                'value'             => function($model) {
                    return sprintf('%s %s', Lang::t($model->model_type_label), Lang::t($model->model_brand_label));
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
                }
            ],
            'serial'            => [
                'filterAttribute'   => 'serial_like'
            ],
            'last_move'         => [
                'label'             => Yii::t('app', 'Last move'),
                'filter'            => false,
                'format'            => 'html',
                'value'             => function($model) {
                    return Yii::t('app', '{0} &nbsp;←&nbsp; {1}', [$model->dst_name, Lang::t($model->src_name)]);
                },
            ],
            'move_type_label'   => [
                'filter'            => false,
                'enableSorting'     => false,
                'format'            => 'html',
                'value'             => function($model) {
                    return Lang::t($model->move_type_label);
                },
            ],
            'move_time'         => [
                'filter'            => false,
                'format'            => ['date', 'php:medium'],
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
                'value'             => function($model) {
                    $out = '';
                    if ($model['move_remote_ticket']) {
                        $out .= $model['move_remote_ticket'] . "<br>";
                    }
                    if ($model['move_hm_ticket']) {
                        $out .= $model['move_hm_ticket'] . "<br>";
                    }
                    if ($model['move_remotehands_label']) {
                        $out .= $model['move_remotehands_label'] . "<br>";
                    }
                    return $out;
                }
            ],
            'actions'           => [
                'class'             => ActionColumn::className(),
                'template'          => '{view} {update}',
                'header'            => Yii::t('app', 'Actions'),
            ],
        ];
    }
}
