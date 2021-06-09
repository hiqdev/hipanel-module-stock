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
use hipanel\grid\ColspanColumn;
use hipanel\modules\stock\models\ModelGroup;
use hipanel\modules\stock\models\ModelGroupSearch;
use hipanel\modules\stock\Module;
use Yii;
use yii\helpers\Html;

class ModelGroupGridView extends BoxedGridView
{
    /** @var ModelGroupSearch */
    public $filterModel;

    private Module $module;

    public function __construct(Module $module, $config = [])
    {
        $this->module = $module;

        parent::__construct($config);
    }

    public function columns()
    {
        return array_merge(parent::columns(), [
            'tableInfoRow' => [
                'class' => ColspanColumn::class,
                'label' => '',
                'columns' => [
                    [
                        'format' => 'raw',
                        'value' => function () {
                            return '&nbsp;';
                        },
                    ],
                    [
                        'label' => Yii::t('hipanel:stock', 'Stock'),
                        'contentOptions' => ['class' => 'text-center'],
                        'value' => function () {
                            return Yii::t('hipanel:stock', 'Stock');
                        }
                    ],
                    [
                        'label' => Yii::t('hipanel:stock', 'RMA'),
                        'contentOptions' => ['class' => 'text-center'],
                        'value' => function () {
                            return Yii::t('hipanel:stock', 'RMA');
                        }
                    ],
                    [
                        'label' => Yii::t('hipanel:stock', 'Limit'),
                        'contentOptions' => ['class' => 'text-center'],
                        'value' => function () {
                            return Yii::t('hipanel:stock', 'Limit');
                        }
                    ],
                    [
                        'value' => function () {
                            return '&nbsp;';
                        },
                    ],
                ],
            ],
            'name' => [
                'headerOptions' => [
                    'class' => 'text-right',
                ],
                'filterOptions' => [
                    'class' => 'text-right',
                ],
                'contentOptions' => [
                    'class' => 'text-right',
                ],
                'filterAttribute' => 'name_ilike',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(Html::encode($model->name), ['@model-group/view', 'id' => $model->id], ['class' => 'text-bold']);
                }
            ],
            'descr' => [
                'enableSorting' => false,
                'filterAttribute' => 'descr_like',
            ],
            'actions' => [
                'class' => ActionColumn::class,
                'template' => '{view} {update}',
                'header' => Yii::t('hipanel', 'Actions'),
            ],
        ], $this->getLimitColumns());
    }

    protected function getLimitColumns(): array
    {
        $columns = [];
        foreach ($this->module->stocksList as $type => $label) {
            $columns[$type] = [
                'class' => ColspanColumn::class,
                'label' => $label,
                'headerOptions' => [
                    'class' => 'text-center',
                ],
                'columns' => [
                    [
                        'format' => 'raw',
                        'value' => fn() => '&nbsp;',
                    ],
                    [
                        'label' => Yii::t('hipanel:stock', 'Stock'),
                        'contentOptions' => ['class' => 'text-center'],
                        'format' => 'raw',
                        'value' => function (ModelGroup $model) use ($type) {
                            $html = '';

                            if ($model->limits[$type]['res_stock']) {
                                $html .= $model->limits[$type]['res_stock'] . '+';
                            }

                            $html .= Html::tag('strong', Html::encode($model->limits[$type]['stock']), ['class' => 'text-error']);
                            return $html;
                        }
                    ],
                    [
                        'label' => Yii::t('hipanel:stock', 'RMA'),
                        'contentOptions' => ['class' => 'text-center'],
                        'format' => 'raw',
                        'value' => function (ModelGroup $model) use ($type) {
                            $html = '';

                            if ($model->limits[$type]['res_rma']) {
                                $html .= $model->limits[$type]['res_rma'] . '+';
                            }

                            $html .= Html::tag('strong', Html::encode($model->limits[$type]['rma']), ['class' => 'text-danger']);
                            return $html;
                        }
                    ],
                    [
                        'label' => Yii::t('hipanel:stock', 'Limit'),
                        'contentOptions' => function (ModelGroup $model) use ($type) {
                            $short = $model->limits[$type]['limit'] > $model->limits[$type]['stock'];
                            return ['class' => 'text-center' . ($short ? ' bg-danger' : '')];
                        },
                        'format' => 'raw',
                        'value' => function (ModelGroup $model) use ($type) {
                            return Html::tag('strong', Html::encode($model->limits[$type]['limit']));
                        }
                    ],
                    [
                        'format' => 'raw',
                        'value' => function () {
                            return '&nbsp;';
                        },
                    ],
                ]
            ];
        }

        return $columns;
    }
}
