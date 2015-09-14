<?php

namespace hipanel\modules\stock\grid;

use hipanel\grid\ActionColumn;
use hipanel\grid\BoxedGridView;
use Yii;
use yii\helpers\Html;

class ModelGridView extends BoxedGridView
{
    public static function defaultColumns()
    {
        return [
            'type' => [],
            'brand' => [],
            'model' => [],
            'last_prices' => [
                'enableSorting' => false,
                'filter' => false,
                'value' => function($model) {
                    return 1;
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