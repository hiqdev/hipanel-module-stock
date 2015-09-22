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
            'dtg' => [
                'enableSorting' => false,
                'filter' => false,
                'format' => 'raw',
                'value' => function($model) {
                    // $out .= Html::tag('span', sprintf('%s: %s', $k, $v), ['class' => 'btn btn-info btn-xs']) . '&nbsp;';
                    return $model->getDcs('dtg');
                }
            ],
            'sdg' => [
                'enableSorting' => false,
                'filter' => false,
                'format' => 'raw',
                'value' => function($model) {
                    // $out .= Html::tag('span', sprintf('%s: %s', $k, $v), ['class' => 'btn btn-info btn-xs']) . '&nbsp;';
                    return $model->getDcs('sdg');
                }
            ],
            'm3' => [
                'enableSorting' => false,
                'filter' => false,
                'format' => 'raw',
                'value' => function($model) {
                    // $out .= Html::tag('span', sprintf('%s: %s', $k, $v), ['class' => 'btn btn-info btn-xs']) . '&nbsp;';
                    return $model->getDcs('m3');
                }
            ],
            'last_prices' => [
                'enableSorting' => false,
                'filter' => false,
                'value' => function($model) {
                    return $model->showModelPrices($model->last_prices);
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