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

    public function __construct(private readonly StockRepository $stockAliasRepository, $config = [])
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
                'label' => false,
                'columns' => [
                    ...array_map(static fn(InStockPartState $partState) => [
                        'label' => $partState->label(),
                        'contentOptions' => ['class' => 'text-center'],
                        'format' => 'raw',
                        'value' => fn(): string => Html::tag('strong', $partState->label()),
                    ], InStockPartState::cases()),
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
                'filterAttribute' => 'descr_ilike',
            ],
        ], $this->getStockColumns());
    }

    protected function getStockColumns(): array
    {
        $columns = [];
        foreach ($this->stockAliasRepository->getStoredAliases() as $alias) {
            $columns[$alias] = [
                'class' => ColspanColumn::class,
                'filterOptions' => ['class' => 'test-stock_alias text-center'],
                'label' => $alias,
                'headerOptions' => [
                    'class' => 'text-center',
                    'data-test-stock_alias' => $alias,
                ],
                'columns' => [
                    ...array_map(static fn(InStockPartState $partState) => [
                        'label' => Html::tag('strong', $partState->label()),
                        'contentOptions' => ['class' => 'text-center'],
                        'encodeLabel' => false,
                        'format' => 'raw',
                        'value' => function (ModelGroup $modelGroup) use ($alias, $partState): ?string {
                            if (!isset($modelGroup->limits[$alias][$partState->name])) {
                                return null;
                            }

                            return Html::a(
                                $modelGroup->limits[$alias][$partState->name],
                                [
                                    '@part/index',
                                    'PartSearch[model_group_id]' => $modelGroup->id,
                                    'PartSearch[stock_location]' => implode(':', ['stock_alias', $alias]),
                                    'PartSearch[stock_location_state]' => $partState->name,
                                ]
                            );
                        },
                    ], InStockPartState::cases()),
                ],
            ];
        }

        return $columns;
    }
}
