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
use hipanel\modules\stock\helpers\ProfitColumns;
use hipanel\modules\stock\models\Move;
use hipanel\modules\stock\models\Part;
use hipanel\modules\stock\widgets\combo\LocationsCombo;
use hipanel\modules\stock\widgets\combo\OrderCombo;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hipanel\widgets\SummaryHook;
use Yii;
use yii\base\Model;
use yii\helpers\Html;

class PartGridView extends BoxedGridView
{
    /**
     * @return array
     */
    private function getProfitColumns(): array
    {
        return ProfitColumns::getGridColumns($this, 'object_ids');
    }

    public function columns()
    {
        return array_merge(parent::columns(), $this->getProfitColumns(), [
            'serial' => [
                'label' => Yii::t('hipanel:stock', 'Serial'),
                'filterOptions' => ['class' => 'narrow-filter'],
                'filterAttribute' => 'serial_ilike',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(Html::encode($model->serial), ['@part/view', 'id' => $model->id], ['class' => 'text-bold']);
                },
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
                    if (Yii::$app->user->can('model.read')) {
                        return Html::a(Html::encode($model->partno), ['@model/view', 'id' => $model->model_id], [
                            'data' => ['toggle' => 'tooltip'],
                            'title' => Html::encode("{$model->model_type_label} {$model->model_brand_label} / {$model->model_label}"),
                        ]);
                    }

                    return $model->partno;
                },
            ],
            'reserve' => [
                'attribute' => 'reserve',
                'format' => 'text',
                'visible' => Yii::$app->user->can('move.create'),
                'contentOptions' => [
                    'style' => 'word-break: break-all;',
                ],
            ],
            'model' => [
                'attribute' => 'model',
                'format' => 'raw',
                'label' => Yii::t('hipanel:stock', 'Model'),
                'value' => function ($model) {
                    $modelLabel = Html::encode($model->model_label);
                    if (Yii::$app->user->can('model.read')) {
                        return Html::a($modelLabel, ['@model/view', 'id' => $model->model_id]);
                    }

                    return $modelLabel;
                },
            ],
            'model_type' => [
                'class' => RefColumn::class,
                'filterOptions' => ['class' => 'narrow-filter'],
                'gtype' => 'type,model',
                'label' => Yii::t('hipanel:stock', 'Type'),
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
            'company_id' => [
                'class' => CompanyColumn::class,
                'visible' => Yii::$app->user->can('order.create'),
            ],
            'last_move' => [
                'label' => Yii::t('hipanel:stock', 'Last move'),
                'sortAttribute' => 'dst_name',
                'format' => 'raw',
                'visible' => Yii::$app->user->can('move.read'),
                'value' => function ($model) {
                    return Html::tag('b', Html::encode($model->dst_name)) . '&nbsp;←&nbsp;' . Html::encode($model->src_name);
                },
            ],
            'move_type_and_date' => [
                'label' => Yii::t('hipanel', 'Type') . ' / ' . Yii::t('hipanel', 'Date'),
                'sortAttribute' => 'move_time',
                'format' => 'raw',
                'value' => function ($model) {
                    $linkToMove = Html::a(Html::encode($model->move_type_label), [
                        '@move/index',
                        'MoveSearch' => ['serial_like' => Html::encode($model->serial)],
                    ], ['class' => 'text-bold']);

                    return $linkToMove . ' ' . Html::tag('nobr', Yii::$app->formatter->asDate($model->move_time));
                },
            ],
            'move_type_label' => [
                'filter' => false,
                'enableSorting' => false,
                'attribute' => 'move_type_label',
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
                'visible' => Yii::$app->user->can('order.read'),
            ],
            'order_name' => [
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
                'visible' => Yii::$app->user->can('order.read'),
                'value' => function (Part $model): string {
                    return HTML::a(Html::encode($model->order_name), ['@order/view', 'id' => $model->order_id]);
                },
            ],
            'dc_ticket' => [
                'filter' => false,
                'enableSorting' => false,
                'format' => 'raw',
                'visible' => Yii::$app->user->can('move.read'),
                'value' => function ($model) {
                    $out = '';
                    if ($model['move_remote_ticket']) {
                        $out .= Html::encode($model['move_remote_ticket']) . '<br>';
                    }
                    if ($model['move_hm_ticket']) {
                        $out .= Html::encode($model['move_hm_ticket']) . '<br>';
                    }
                    if ($model['move_remotehands_label']) {
                        $out .= Html::encode($model['move_remotehands_label']) . '<br>';
                    }

                    return $out;
                },
            ],
            'price' => [
                'class' => CurrencyColumn::class,
                'filterAttribute' => 'currency',
                'visible' => Yii::$app->user->can('order.read'),
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
                'footer' => '<b>' . Yii::t('hipanel:stock', 'TOTAL on screen') . '</b>',
            ],
            'selling_price' => [
                'filterAttribute' => 'selling_currency',
                'filter' => false,
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
                    return LocationsCombo::widget([
                        'model' => $model,
                        'attribute' => $attribute,
                        'formElementSelector' => 'td',
                    ]);
                },
                'format' => 'raw',
                'value' => static function (Part $model) {
                    if ($model->isTrashed()) {
                        return Html::encode($model->place);
                    }

                    return Html::tag('b', Html::encode($model->dst_name)) . Html::tag('span', Html::encode($model->place), ['style' => 'margin-left:1em']);
                },
            ],
            'model_group' => [
                'label' => Yii::t('hipanel:stock', 'Model group'),
                'enableSorting' => false,
                'filter' => false,
                'format' => 'raw',
                'value' => function (Model $model) {
                    return Html::a(Html::encode($model->model->group), ['@model-group/view', 'id' => $model->model->group_id]);
                },
            ],
            'actions' => [
                'class' => ActionColumn::class,
                'template' => '{view} {update}',
                'header' => Yii::t('hipanel', 'Actions'),
            ],
            'move_descr' => [
                'filterOptions' => ['class' => 'narrow-filter'],
                'filterAttribute' => 'move_descr_ilike',
                'format' => 'raw',
                'label' => Yii::t('hipanel:stock', 'Move description'),
                'visible' => Yii::$app->user->can('move.read'),
                'value' => function ($model) {
                    return Move::prepareDescr($model->move_descr);
                },
            ],
            'first_move' => [
                'visible' => Yii::$app->user->can('move.read'),
            ],
            'last_move_with_descr' => [
                'label' => Yii::t('hipanel:stock', 'Last move with descr'),
                'format' => 'raw',
                'visible' => Yii::$app->user->can('move.read'),
                'contentOptions' => ['style' => 'display: flex; flex-direction: row; justify-content: space-between; flex-wrap: nowrap;'],
                'value' => function ($model) {
                    $move = Html::tag('span', Html::tag('b', Html::encode($model->dst_name)) . '&nbsp;←&nbsp;' . Html::encode($model->src_name));
                    $descr = Move::prepareDescr(Html::encode($model->move_descr));

                    return implode('', [$move, $descr]);
                },
            ],
        ]);
    }
}
