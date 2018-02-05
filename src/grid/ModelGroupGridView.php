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
use Yii;
use yii\helpers\Html;

class ModelGroupGridView extends BoxedGridView
{
    /** @var ModelGroupSearch */
    public $filterModel;

    public function columns()
    {
        return array_merge(parent::columns(), [
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
        foreach ($this->filterModel->getSupportedLimitTypes() as $type => $label) {
            $columns[$type] = [
                'class' => ColspanColumn::class,
                'label' => $label,
                'headerOptions' => [
                    'class' => 'text-center',
                ],
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
                        'format' => 'raw',
                        'value' => function (ModelGroup $model) use ($type) {
                            $html = '';

                            if ($model->limits[$type]['res_stock']) {
                                $html .= $model->limits[$type]['res_stock'] . '+';
                            }

                            $html .= Html::tag('strong', $model->limits[$type]['stock'], ['class' => 'text-error']);
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

                            $html .= Html::tag('strong', $model->limits[$type]['rma'], ['class' => 'text-danger']);
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
                            return Html::tag('strong', $model->limits[$type]['limit']);
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
