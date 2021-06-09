<?php

namespace hipanel\modules\stock\grid;

use hipanel\modules\stock\helpers\ProfitColumns;
use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

class PartRepresentations extends RepresentationCollection
{
    protected function fillRepresentations()
    {
        $user = Yii::$app->user;
        $this->representations = array_filter([
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => [
                    'checkbox',
                    'model_type', 'model_brand', 'model', 'partno', 'serial',
                    'last_move', 'move_type_and_date', 'move_descr',
                    'order_name', 'company_id',
                ],
            ],
            'brief' => [
                'label' => Yii::t('hipanel', 'brief'),
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
            'detailed' => $user->can('tmp disabled') ? [
                'label' => Yii::t('hipanel', 'detailed'),
                'columns' => [
                    'checkbox',
                    'model_type', 'model_brand',
                    'partno', 'serial',
                    'last_move', 'move_type_and_date',
                    'move_descr', 'order_data', 'dc_ticket',
                ],
            ] : '',
            'selling' => $user->can('order.create') ? [
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
            ] : null,
            'profit-report' => $user->can('order.read-profits') ? [
                'label' => Yii::t('hipanel', 'profit report'),
                'columns' => ProfitColumns::getColumnNames(['checkbox', 'buyer', 'company_id', 'serial', 'partno']),
            ] : null,
            'admin' => $user->can('admin') && $user->can('order.create') ? [
                'label' => Yii::t('hipanel:stock', 'Administrative'),
                'columns' => [
                    'checkbox',
                    'model_type', 'model_brand', 'partno', 'serial', 'place', 'reserve', 'last_move_with_descr', 'move_time',
                ],
            ] : null,
        ]);
    }
}
