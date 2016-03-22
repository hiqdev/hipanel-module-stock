<?php

/**
 * @var array $data
 */


use yii\helpers\Html;

echo \hipanel\grid\GridView::widget([
    'dataProvider' => new \yii\data\ArrayDataProvider([
        'pagination' => false,
        'allModels' => $data,
        'sort' => false,
    ]),
    'summary' => false,
    'columns' => [
        [
            'attribute' => 'type',
            'format' => 'html',
            'label' => Yii::t('hipanel/stock', 'Type'),
            'options' => [
                'style' => 'width: 20%',
            ],
            'value' => function ($models, $key) {
                return Html::tag('strong', Yii::t('hipanel/stock', $key));
            }
        ],
        [
            'label' => Yii::t('hipanel/stock', 'Model'),
            'attribute' => 'model',
            'options' => [
                'style' => 'width: 40%',
            ],
            'format' => 'raw',
            'value' => function ($models) {
                $models_partno = [];
                foreach ($models as $model_id => $parts) {
                    $modelLink = Html::a(reset($parts)->partno, ['@model/view', 'id' => $model_id]);
                    $models_partno[] = (count($parts) > 1 ? count($parts) . 'x' : '') . $modelLink;
                }

                return implode(', ', $models_partno);
            }
        ],
        [
            'attribute' => 'serials',
            'format' => 'html',
            'label' => Yii::t('hipanel/stock', 'Serials'),
            'value' => function ($models) {
                $serials = [];

                foreach ($models as $model_id => $parts) {
                    foreach ($parts as $part) {
                        $serials[] = Html::a($part->serial, ['@part/view', 'id' => $part->id]);
                    }
                }

                return implode(', ', $serials);
            }
        ]
    ]
]);
