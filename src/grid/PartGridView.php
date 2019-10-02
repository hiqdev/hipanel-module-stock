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
use hipanel\grid\CurrencyColumn;
use hipanel\grid\RefColumn;
use hipanel\modules\client\grid\ClientColumn;
use hipanel\modules\stock\helpers\ProfitRepresentations;
use hipanel\modules\stock\models\Move;
use hipanel\modules\stock\models\Order;
use hipanel\modules\stock\models\Part;
use hipanel\modules\stock\models\PartWithProfit;
use hipanel\modules\stock\widgets\combo\OrderCombo;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use Yii;
use yii\base\Model;
use yii\helpers\Html;

class PartGridView extends BoxedGridView
{
    public $locations;

    private function getProfitColumns()
    {
        return ProfitRepresentations::getColumns(function ($attr, $cur): array {
            $valueArray = [
                'value' => function (PartWithProfit $parts) use ($attr, $cur): string {
                    if ($parts->currency === $cur) {
                        return (string)$parts->{$attr};
                    }
                    return '';
                },
                'format' => 'raw',
                'contentOptions' => ['class' => 'right-aligned'],
                'footerOptions' => ['class' => 'right-aligned'],
            ];
            if ($this->showFooter) {
                $valueArray['footer'] = (function () use ($attr, $cur): string {
                    $models = $this->dataProvider->getModels();
                    $sum = array_reduce($models, function (float $sum, PartWithProfit $parts) use ($attr, $cur): float {
                        if ($parts && $parts->currency === $cur) {
                            return $sum + $parts->{$attr};
                        }
                        return $sum;
                    }, 0.0);
                    return empty($sum) ? '' : number_format($sum, 2);
                })();
            }
            return [
                'key' => "{$attr}_{$cur}",
                'value' => $valueArray,
            ];
        });
    }

    public function columns()
    {
        return array_merge(parent::columns(), $this->getProfitColumns(), [
            'serial' => [
                'label' => Yii::t('hipanel:stock', 'Serial'),
                'filterOptions' => ['class' => 'narrow-filter'],
                'filterAttribute' => 'serial_like',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::a($model->serial, ['@part/view', 'id' => $model->id], ['class' => 'text-bold']);
                },
                'footer' => '<b>' . Yii::t('hipanel:stock', 'TOTAL on screen') . '</b>',
            ],
            'main' => [
                'label' => Yii::t('hipanel', 'Type') . ' / ' . Yii::t('hipanel:stock', 'Manufacturer'),
                'sortAttribute' => 'model_type',
                'value' => function ($model) {
                    return $model->model_type_label . ' ' . $model->model_brand_label;
                },
            ],
            'partno' => [
                'filterAttribute' => 'partno_like',
                'filter' => function ($column, $model, $attribute) {
                    return PartnoCombo::widget([
                        'model' => $model,
                        'attribute' => $attribute,
                        'formElementSelector' => 'td',
                    ]);
                },
                'format' => 'raw',
                'label' => Yii::t('hipanel:stock', 'Part No.'),
                'value' => function ($model) {
                    return Html::a($model->partno, ['@model/view', 'id' => $model->model_id], [
                        'data' => ['toggle' => 'tooltip'],
                        'title' => "{$model->model_type_label} {$model->model_brand_label} / {$model->model_label}",
                    ]);
                },
            ],
            'reserve' => [
                'attribute' => 'reserve',
                'format' => 'text',
                'contentOptions' => [
                    'style' => 'word-break: break-all;',
                ],
            ],
            'model' => [
                'attribute' => 'model',
                'format' => 'raw',
                'label' => Yii::t('hipanel:stock', 'Model'),
                'value' => function ($model) {
                    return Html::a($model->model_label, ['@model/view', 'id' => $model->model_id]);
                },
            ],
            'model_type' => [
                'class' => RefColumn::class,
                'filterOptions' => ['class' => 'narrow-filter'],
                'gtype' => 'type,model',
                'label' => Yii::t('hipanel:stock','Type'),
                'value' => function ($model) {
                    return $model->model_type_label;
                },
            ],
            'model_brand' => [
                'class' => RefColumn::class,
                'filterOptions' => ['class' => 'narrow-filter'],
                'gtype' => 'type,brand',
                'label' => Yii::t('hipanel:stock', 'Manufacturer'),
                'value' => function ($model) {
                    return $model->model_brand_label;
                },
            ],
            'company' => [
                'value' => function ($model) {
                    return $model->company;
                },
            ],
            'last_move' => [
                'label' => Yii::t('hipanel:stock', 'Last move'),
                'sortAttribute' => 'dst_name',
                'format' => 'html',
                'value' => function ($model) {
                    return Html::tag('b', $model->dst_name) . '&nbsp;←&nbsp;' . $model->src_name;
                },
            ],
            'move_type_and_date' => [
                'label' => Yii::t('hipanel', 'Type') . ' / ' . Yii::t('hipanel', 'Date'),
                'sortAttribute' => 'move_time',
                'format' => 'raw',
                'value' => function ($model) {
                    $linkToMove = Html::a($model->move_type_label, [
                        '@move/index',
                        'MoveSearch' => ['serial_like' => $model->serial],
                    ], ['class' => 'text-bold']);

                    return $linkToMove . ' ' . Html::tag('nobr', Yii::$app->formatter->asDate($model->move_time));
                },
            ],
            'move_type_label' => [
                'filter' => false,
                'enableSorting' => false,
                'format' => 'html',
                'value' => function ($model) {
                    return $model->move_type_label;
                },
            ],
            'move_time' => [
                'filter' => false,
                'format' => 'datetime',
            ],
            'move_date' => [
                'attribute' => 'move_time',
                'filter' => false,
                'format' => 'date',
                'sortAttribute' => 'move_time',
            ],
            'create_time' => [
                'filter' => false,
                'format' => 'datetime',
            ],
            'create_date' => [
                'attribute' => 'create_time',
                'filter' => false,
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::tag('nobr', Yii::$app->formatter->asDate($model->create_time));
                },
            ],
            'order_data' => [
                'filter' => false,
                'enableSorting' => false,
            ],
            'order_no' => [
                'attribute' => 'order_id',
                'filterAttribute' => 'order_id',
                'filter' => function ($column, $model, $attribute) {
                    return OrderCombo::widget([
                        'model' => $model,
                        'attribute' => $attribute,
                        'formElementSelector' => 'td',
                    ]);
                },
                'filterOptions' => ['class' => 'narrow-filter'],
                'format' => 'raw',
                'value' => function (Part $model): string {
                    return HTML::a($model->order_name, ['@order/view', 'id' => $model->order_id]) .
                        '</br>' . $model->order_no;
                }
            ],
            'dc_ticket' => [
                'filter' => false,
                'enableSorting' => false,
                'format' => 'html',
                'value' => function ($model) {
                    $out = '';
                    if ($model['move_remote_ticket']) {
                        $out .= $model['move_remote_ticket'] . '<br>';
                    }
                    if ($model['move_hm_ticket']) {
                        $out .= $model['move_hm_ticket'] . '<br>';
                    }
                    if ($model['move_remotehands_label']) {
                        $out .= $model['move_remotehands_label'] . '<br>';
                    }

                    return $out;
                },
            ],
            'price' => [
                'class' => CurrencyColumn::class,
                'filterAttribute' => 'currency',
                'filter' => function ($column, $model, $attribute) {
                    $values = ['usd' => 'USD', 'eur' => 'EUR'];

                    return Html::activeDropDownList($model, 'currency', $values, [
                        'class' => 'form-control',
                        'prompt' => Yii::t('hipanel', '----------'),
                    ]);
                },
            ],
            'buyer' => [
                'class' => ClientColumn::class,
                'nameAttribute' => 'buyer',
                'idAttribute' => 'buyer_id',
                'attribute' => 'buyer',
            ],
            'selling_price' => [
                'filterAttribute' => 'selling_currency',
                'filter' => false,
                'format' => 'raw',
                'value' => function ($model) {
                    return Yii::$app->formatter->asCurrency($model->selling_price, $model->selling_currency);
                },
                'visible' => Yii::$app->user->can('bill.read'),
            ],
            'selling_time' => [
                'filter' => false,
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::tag('nobr', Yii::$app->formatter->asDateTime($model->selling_time));
                },
            ],
            'place' => [
                'filter' => function ($column, $model, $attribute) {
                    return Html::activeDropDownList($model, 'place', $this->locations, [
                        'class' => 'form-control',
                        'prompt' => Yii::t('hipanel', '----------'),
                    ]);
                },
            ],
            'model_group' => [
                'label' => Yii::t('hipanel:stock', 'Model group'),
                'enableSorting' => false,
                'filter' => false,
                'format' => 'raw',
                'value' => function (Model $model) {
                    return Html::a(Html::encode($model->model->group), ['@model-group/view', 'id' => $model->model->group_id]);
                }
            ],
            'actions' => [
                'class' => ActionColumn::class,
                'template' => '{view} {update}',
                'header' => Yii::t('hipanel', 'Actions'),
            ],
            'move_descr' => [
                'filterOptions' => ['class' => 'narrow-filter'],
                'filterAttribute' => 'move_descr_ilike',
                'format' => 'html',
                'label' => Yii::t('hipanel:stock', 'Move description'),
                'value' => function ($model) {
                    return Move::prepareDescr($model->move_descr);
                },
            ],
        ]);
    }
}
