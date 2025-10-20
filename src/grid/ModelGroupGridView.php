<?php

declare(strict_types=1);


namespace hipanel\modules\stock\grid;

use hipanel\grid\ActionColumn;
use hipanel\grid\BoxedGridView;
use hipanel\grid\ColspanColumn;
use hipanel\grid\DataColumn;
use hipanel\modules\stock\models\ModelGroup;
use hipanel\modules\stock\models\ModelGroupSearch;
use hipanel\modules\stock\repositories\StockRepository;
use Yii;
use yii\helpers\Html;

/**
 *
 * @property-read array $limitColumns
 */
class ModelGroupGridView extends BoxedGridView
{
    /** @var ModelGroupSearch */
    public $filterModel;
    public DataColumn $fakeColumn;

    public function __construct(private readonly StockRepository $stockRepository, $config = [])
    {
        $this->fakeColumn = new class extends DataColumn {
            public function init(): void
            {
                $this->label = '';
                $this->format = 'raw';
                $this->value = fn() => '';
                $this->contentOptions = ['style' => 'padding: 0;'];
                $this->headerOptions = ['style' => 'padding: 0;'];
                $this->options = ['style' => 'padding: 0;'];
            }
        };
        parent::__construct($config);
    }

    public function columns(): array
    {
        return array_merge(parent::columns(), [
            'tableInfoRow' => [
                'class' => ColspanColumn::class,
                'label' => '',
                'columns' => [
                    [
                        'class' => $this->fakeColumn::class,
                    ],
                    [
                        'label' => Yii::t('hipanel:stock', 'Stock'),
                        'contentOptions' => ['class' => 'text-center'],
                        'value' => function () {
                            return Yii::t('hipanel:stock', 'Stock');
                        },
                    ],
                    [
                        'label' => Yii::t('hipanel:stock', 'RMA'),
                        'contentOptions' => ['class' => 'text-center'],
                        'value' => function () {
                            return Yii::t('hipanel:stock', 'RMA');
                        },
                    ],
                    [
                        'label' => Yii::t('hipanel:stock', 'Limit'),
                        'contentOptions' => ['class' => 'text-center'],
                        'value' => function () {
                            return Yii::t('hipanel:stock', 'Limit');
                        },
                    ],
                    [
                        'class' => $this->fakeColumn::class,
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
                'value' => fn($model) => Html::a(
                    Html::encode($model->name),
                    ['@model-group/view', 'id' => $model->id],
                    ['class' => 'text-bold']
                ),
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
        foreach ($this->stockRepository->getStockList() as $type => $label) {
            $columns[$type] = [
                'class' => ColspanColumn::class,
                'filterOptions' => ['class' => 'test-stock_alias'],
                'label' => $label,
                'headerOptions' => [
                    'class' => 'text-center',
                    'data-test-stock_alias' => $label,
                ],
                'columns' => [
                    [
                        'class' => $this->fakeColumn::class,
                    ],
                    [
                        'label' => Yii::t('hipanel:stock', 'Stock'),
                        'contentOptions' => ['class' => 'text-center'],
                        'format' => 'raw',
                        'value' => function (ModelGroup $model) use ($type) {
                            $html = '';

                            if (isset($model->limits[$type]['res_stock'])) {
                                $html .= Html::encode($model->limits[$type]['res_stock']) . '+';
                            }

                            if (isset($model->limits[$type]['stock'])) {
                                $html .= Html::tag('strong', Html::encode($model->limits[$type]['stock']), ['class' => 'text-error']);
                            }

                            return $html;
                        },
                    ],
                    [
                        'label' => Yii::t('hipanel:stock', 'RMA'),
                        'contentOptions' => ['class' => 'text-center'],
                        'format' => 'raw',
                        'value' => function (ModelGroup $model) use ($type) {
                            $html = '';

                            if (isset($model->limits[$type]['res_rma'])) {
                                $html .= Html::encode($model->limits[$type]['res_rma']) . '+';
                            }
                            if (isset($model->limits[$type]['rma'])) {
                                $html .= Html::tag('strong', Html::encode($model->limits[$type]['rma']), ['class' => 'text-danger']);
                            }

                            return $html;
                        },
                    ],
                    [
                        'label' => Yii::t('hipanel:stock', 'Limit'),
                        'contentOptions' => function (ModelGroup $model) use ($type) {
                            $short = ($model->limits[$type]['limit'] ?? 0) > ($model->limits[$type]['stock'] ?? 0);

                            return ['class' => 'text-center' . ($short ? ' bg-danger' : '')];
                        },
                        'format' => 'raw',
                        'value' => function (ModelGroup $model) use ($type) {
                            return isset($model->limits[$type]['limit']) ? Html::tag(
                                'strong',
                                Html::encode($model->limits[$type]['limit'])
                            ) : null;
                        },
                    ],
                    [
                        'class' => $this->fakeColumn::class,
                    ],
                ],
            ];
        }

        return $columns;
    }
}
