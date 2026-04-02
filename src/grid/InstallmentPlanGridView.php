<?php
/**
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2026, HiQDev (http://hiqdev.com/)
 */

declare(strict_types=1);

namespace hipanel\modules\stock\grid;

use hipanel\grid\ActionColumn;
use hipanel\grid\BoxedGridView;
use hipanel\grid\CurrencyColumn;
use hipanel\modules\stock\models\InstallmentPlan;
use hipanel\modules\stock\widgets\combo\InstallmentPlanStateCombo;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use Yii;
use yii\helpers\Html;

class InstallmentPlanGridView extends BoxedGridView
{
    public function columns(): array
    {
        return array_merge(parent::columns(), [
            'serialno' => [
                'label' => Yii::t('hipanel:stock', 'Serial'),
                'filterOptions' => ['class' => 'narrow-filter'],
                'filterAttribute' => 'serialno_ilike',
                'format' => 'raw',
                'value' => fn(InstallmentPlan $model) => Html::a(Html::encode($model->serialno), ['@part/view', 'id' => $model->part_id], ['class' => 'text-bold']),
            ],
            'model' => [
                'filterAttribute' => 'model_like',
                'filter' => function ($column, $model, $attribute) {
                    return PartnoCombo::widget([
                        'model' => $model,
                        'attribute' => $attribute,
                        'formElementSelector' => 'td',
                    ]);
                },
                'format' => 'raw',
                'label' => Yii::t('hipanel:stock', 'Part No.'),
                'value' => static function (InstallmentPlan $model): string {
                    $partNo = Html::encode($model->model);
                    if (Yii::$app->user->can('model.read')) {
                        return Html::a($partNo, ['@model/view', 'id' => $model->model_id], [
                            'data' => ['toggle' => 'tooltip'],
                            'title' => Html::encode(sprintf(
                                "%s %s",
                                Yii::t('hipanel:stock', $model->part_type),
                                Yii::t('hipanel:stock', $model->brand),
                            )),
                        ]);
                    }

                    return $partNo;
                },
            ],
            'device' => [
                'filterAttribute' => 'device_like',
                'format' => 'raw',
                'value' => static function (InstallmentPlan $model) {
                    return Html::tag('b', Html::encode($model->device), ['style' => 'margin-left:1em']);
                },
            ],
            'state' => [
                'filterAttribute' => 'state',
                'filter' => static function ($column, $model, $attribute) {
                    return InstallmentPlanStateCombo::widget([
                        'model' => $model,
                        'attribute' => $attribute,
                        'formElementSelector' => 'td',
                    ]);
                },
                'format' => 'raw',
                'value' => static function (InstallmentPlan $model) {
                    $labelClass = match ($model->state) {
                        InstallmentPlan::STATE_FINISHED => 'label-success',
                        InstallmentPlan::STATE_ONGOING  => 'label-info',
                        InstallmentPlan::STATE_BUYOUT   => 'label-warning',
                        default                         => 'label-danger',
                    };

                    return Html::tag('span', Html::encode($model->state), ['class' => "label {$labelClass}"]);
                },
            ],
            'expected_sum' => [
                'class' => CurrencyColumn::class,
                'filter' => false,
                'attribute' => 'expected_sum',
                'colors' => ['danger' => 'warning'],
                'headerOptions' => ['class' => 'text-right'],
                'contentOptions' => function (InstallmentPlan $model) {
                    return ['class' => 'text-right' . ($model->expected_sum > 0 ? ' text-bold' : '')];
                },
            ],
            'expected_monthly_sum' => [
                'class' => CurrencyColumn::class,
                'filter' => false,
                'attribute' => 'expected_monthly_sum',
                'colors' => ['danger' => 'warning'],
                'headerOptions' => ['class' => 'text-right'],
                'contentOptions' => function (InstallmentPlan $model) {
                    return ['class' => 'text-right' . ($model->expected_monthly_sum > 0 ? ' text-bold' : '')];
                },
            ],
            'left_sum' => [
                'class' => CurrencyColumn::class,
                'filter' => false,
                'attribute' => 'left_sum',
                'colors' => ['danger' => 'warning'],
                'headerOptions' => ['class' => 'text-right'],
                'contentOptions' => function (InstallmentPlan $model) {
                    return ['class' => 'text-right' . ($model->left_sum > 0 ? ' text-bold' : '')];
                },
            ],
            'charged_sum' => [
                'class' => CurrencyColumn::class,
                'filter' => false,
                'attribute' => 'charged_sum',
                'colors' => ['danger' => 'warning'],
                'headerOptions' => ['class' => 'text-right'],
                'contentOptions' => function (InstallmentPlan $model) {
                    return ['class' => 'text-right' . ($model->charged_sum > 0 ? ' text-bold' : '')];
                },
            ],
            'quantity' => [
                'filter' => false,
            ],
            'since' => [
                'filter' => false,
                'format' => 'raw',
                'value' => fn(InstallmentPlan $model) => $model->since
                    ? Html::encode((new \DateTimeImmutable($model->since))->format('Y-m-d'))
                    : '—',
            ],
            'till' => [
                'filter' => false,
                'format' => 'raw',
                'value' => fn(InstallmentPlan $model) => $model->till
                    ? Html::encode((new \DateTimeImmutable($model->till))->format('Y-m-d'))
                    : '—',
            ],
            'actions' => [
                'class' => ActionColumn::class,
                'template' => '{view}',
                'header' => Yii::t('hipanel', 'Actions'),
            ],
        ]);
    }
}
