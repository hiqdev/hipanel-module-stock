<?php

namespace hipanel\modules\stock\grid;

use hipanel\modules\stock\helpers\ProfitColumns;
use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

class PartRepresentations extends RepresentationCollection
{
    protected function fillRepresentations()
    {
        $this->representations = array_filter([
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => [
                    'checkbox',
                    'model_type', 'model_brand', 'partno', 'serial',
                    'last_move', 'move_type_and_date', 'move_descr',
                    'order_name', 'company_id',
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
            'selling' => [
                'label' => Yii::t('hipanel:stock', 'selling'),
                'columns' => [
                    'checkbox',
                    'buyer',
                    'last_move',
                    'model_type',
                    'partno',
                    'serial',
                    'price',
                    'selling_price',
                    'selling_time',
                ]
            ],
            'profit-report' => Yii::$app->user->can('order.read-profits') ? [
                'label' => Yii::t('hipanel', 'profit report'),
                'columns' => ProfitColumns::getColumns(['checkbox', 'buyer', 'company_id', 'serial', 'partno']),
            ] : null,
        ]);
    }
}
