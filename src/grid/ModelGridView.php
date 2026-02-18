<?php declare(strict_types=1);

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
use hipanel\grid\RefColumn;
use hipanel\modules\stock\helpers\StockLocationsProvider;
use hipanel\modules\stock\models\Model;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\User;

class ModelGridView extends BoxedGridView
{
    private array $stockColumns;

    public function __construct(
        private readonly StockLocationsProvider $locationsProvider,
        private readonly User $user,
        $config = []
    )
    {
        $this->stockColumns = $this->generateStockColumns();
        parent::__construct($config);
    }

    public function columns()
    {
        return array_merge(parent::columns(), [
            'type' => [
                'filterOptions' => ['class' => 'narrow-filter'],
                'class' => RefColumn::class,
                'gtype' => 'type,model',
                'i18nDictionary' => 'hipanel.stock.order',
                'value' => function ($model) {
                    return $model->type_label;
                },
            ],
            'state' => [
                'class' => RefColumn::class,
                'gtype' => 'state,model',
                'i18nDictionary' => 'hipanel.stock.order',
                'value' => function ($model) {
                    return $model->state_label;
                },
            ],
            'brand' => [
                'filterOptions' => ['class' => 'narrow-filter'],
                'class' => RefColumn::class,
                'gtype' => 'type,brand',
                'i18nDictionary' => 'hipanel.stock.order',
                'value' => function ($model) {
                    return $model->brand_label;
                },
            ],
            'model' => [
                'filterAttribute' => 'model_like',
                'filterOptions' => ['class' => 'narrow-filter'],
            ],
            'partno' => [
                'enableSorting' => false,
                'filterAttribute' => 'partno_like',
                'format' => 'raw',
                'value' => function (Model $model) {
                    return Html::a(Html::encode($model->partno), [
                        '@model/view',
                        'id' => $model->id,
                    ], ['class' => 'text-bold']);
                },
            ],
            'descr' => [
                'enableSorting' => false,
                'filterAttribute' => 'descr_like',
            ],
            'last_prices' => [
                'label' => Yii::t('hipanel:stock', 'Last price'),
                'enableSorting' => false,
                'filter' => false,
                'format' => 'raw',
                'value' => function (Model $model) {
                    return $model->showModelPrices($model->last_prices);
                },
                'visible' => $this->user->can('move.read-all'),
            ],
            'model_group' => [
                'label' => Yii::t('hipanel:stock', 'Group'),
                'enableSorting' => false,
                'filter' => false,
                'format' => 'raw',
                'value' => function (Model $model) {
                    $group = Html::encode($model->group);

                    return Html::a($group, ['@model-group/view', 'id' => $model->group_id], [
                        'title' => $group,
                        'style' => 'display: inline-block; width: 120px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;',
                    ]);
                },
            ],
            'actions' => [
                'class' => ActionColumn::class,
                'template' => '{view} {update}',
                'header' => Yii::t('hipanel', 'Actions'),
            ],
        ], $this->stockColumns);
    }

    private function generateStockColumns(): array
    {
        $result = [];
        $locations = ArrayHelper::index($this->locationsProvider->getAllLocations(), 'id');
        foreach ($this->locationsProvider->getLocations() as $key) {
            $location = $locations[$key];
            $icon = Html::tag('span', null, [
                    'class' => "fa fa-fw " . $location->icon,
                ]
            );
            $label = $location->label;
            $result[$key] = [
                'attribute' => $key,
                'label' => implode(' ', [$icon, $label]),
                'headerOptions' => ['title' => $location->type->value, 'style' => 'white-space: nowrap;'],
                'encodeLabel' => false,
                'enableSorting' => false,
                'filter' => false,
                'format' => 'raw',
                'value' => fn(Model $model) => $model->renderReserves($key),
            ];
        }

        return $result;
    }
}
