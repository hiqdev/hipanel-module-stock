<?php
namespace hipanel\modules\stock\grid;

use hipanel\grid\ActionColumn;
use hipanel\grid\BoxedGridView;
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
                'enableSorting' => false,
                'filterAttribute' => 'partno_like',
            ],
            'descr' => [
                'enableSorting' => false,
                'filterAttribute' => 'descr_like',
            ],
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