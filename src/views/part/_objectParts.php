<?php

/*
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

/**
 * @var array $data
 */

use hipanel\helpers\StringHelper;
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
            'label' => Yii::t('hipanel:stock', 'Type'),
            'options' => [
                'style' => 'width: 20%',
            ],
            'value' => function ($models, $key) {
                return Html::tag('strong', Yii::t('hipanel:stock', $key));
            },
        ],
        [
            'label' => Yii::t('hipanel:stock', 'Model'),
            'attribute' => 'model',
            'options' => [
                'style' => 'width: 40%',
            ],
            'format' => 'raw',
            'value' => function ($models) {
                $models_partno = [];
                foreach ($models as $model_id => $parts) {
                    $modelLink = Yii::$app->user->can('model.read')
                        ? Html::a(reset($parts)->partno, ['@model/view', 'id' => $model_id])
                        : reset($parts)->partno;

                    $models_partno[] = (count($parts) > 1 ? count($parts) . 'x ' : '') . $modelLink;
                }

                return implode(', ', $models_partno);
            },
        ],
        [
            'attribute' => 'serials',
            'format' => 'html',
            'label' => Yii::t('hipanel:stock', 'Serials'),
            'value' => function ($models) {
                $serials = [];

                foreach ($models as $model_id => $parts) {
                    foreach ($parts as $part) {
                        $serials[] = Html::a($part->serial, ['@part/view', 'id' => $part->id]);
                    }
                }

                return implode(', ', $serials);
            },
        ],
        [
            'label' => Yii::t('hipanel:stock', 'Manufacturer'),
            'attribute' => 'model_brand_label',
            'value' => static function ($models) {
                return implode(', ', array_unique(array_map(fn ($parts) => reset($parts)->model_brand_label, $models)));
            }
        ],
        [
            'label' => Yii::t('hipanel.finance.price', 'Price'),
            'attribute' => 'price',
            'visible' => Yii::$app->user->can('order.read'),
            'value' => static function ($models) {
                return implode(', ', array_map(static function ($parts) {
                    $part = reset($parts);
                    if (empty($part->price)) {
                        return '';
                    }
                    return  (count($parts) > 1 ? count($parts) . 'x' : '') . $part->price . StringHelper::getCurrencySymbol($part->currency);
                }, $models));
            }
        ],
        [
            'label' => Yii::t('hipanel', 'Date'),
            'attribute' => 'move_time',
            'format' => 'raw',
            'value' => static fn(array $models) => implode(', ', array_map(static function ($parts) {
                $part = reset($parts);
                if (empty($part->price)) {
                    return '';
                }

                return Html::tag('nobr', Yii::$app->formatter->asDate($part->move_time));
            }, $models)),
            'visible' => Yii::$app->user->can('order.read'),
        ],
        [
            'label' => Yii::t('hipanel:stock', 'Order No.'),
            'format' => 'raw',
            'attribute' => 'first_move',
            'visible' => Yii::$app->user->can('order.read'),
            'value' => static function ($models) {
                return implode(', ', array_map(static function ($parts) {
                    $part = reset($parts);
                    return Html::a($part->first_move, ['@order/view', 'id' => $part->order_id]);
                }, $models));
            },
        ],
        [
            'attribute' => 'company',
            'label' => Yii::t('hipanel:stock', 'Company'),
            'visible' => Yii::$app->user->can('part.create'),
            'value' => static function (array $models): string {
                return implode(', ', array_map(static function ($parts) {
                    $part = reset($parts);

                    return $part->company ?? '--';
                }, $models));
            },
        ],
    ],
]);
