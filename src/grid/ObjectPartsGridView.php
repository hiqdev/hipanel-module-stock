<?php

declare(strict_types=1);

namespace hipanel\modules\stock\grid;

use Closure;
use hipanel\grid\BoxedGridView;
use hipanel\grid\RefColumn;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\i18n\Formatter;

class ObjectPartsGridView extends BoxedGridView
{
    public $summary = false;
    public array $data;
    public $boxed = false;
    private Formatter $formater;

    public function init()
    {
        $this->dataProvider = new ArrayDataProvider([
            'pagination' => false,
            'allModels' => $this->data,
            'sort' => false,
            'key' => fn($models): string => $this->getModelType($models),
        ]);
        $this->columns = array_keys($this->columns());
        $this->formater = Yii::$app->formatter;
        parent::init();
    }

    public function columns()
    {
        return [
            'type' => [
                'class' => RefColumn::class,
                'attribute' => 'type',
                'format' => 'html',
                'label' => Yii::t('hipanel:stock', 'Type'),
                'options' => [
                    'style' => 'width: 20%',
                ],
                'i18nDictionary' => 'hipanel:stock',
                'value' => function ($models) {
                    $parts = reset($models);
                    return Html::tag('strong', Yii::t('hipanel:stock', reset($parts)->model_type_label));
                },
            ],
            'model' => [
                'label' => Yii::t('hipanel:stock', 'Model'),
                'attribute' => 'model',
                'options' => [
                    'style' => 'width: 40%',
                ],
                'format' => 'raw',
                'value' => function ($models) {
                    $modelsPartNo = [];
                    foreach ($models as $model_id => $parts) {
                        $modelLink = Yii::$app->user->can('model.read')
                            ? Html::a(reset($parts)->partno, ['@model/view', 'id' => $model_id])
                            : reset($parts)->partno;

                        $modelsPartNo[] = count($parts) . Html::tag('span', ' x ', ['class' => 'text-muted']) . $modelLink;
                    }

                    return implode(', ', $modelsPartNo);
                },
            ],
            'serial' => [
                'attribute' => 'serials',
                'format' => 'html',
                'label' => Yii::t('hipanel:stock', 'Serials'),
                'value' => static function (array $models) {
                    $serials = [];
                    foreach ($models as $parts) {
                        foreach ($parts as $part) {
                            $serials[] = Html::a($part->serial, ['@part/view', 'id' => $part->id]);
                        }
                    }

                    return implode(', ', $serials);
                },
            ],
            'model_brand_label' => [
                'class' => RefColumn::class,
                'label' => Yii::t('hipanel:stock', 'Manufacturer'),
                'attribute' => 'model_brand_label',
                'i18nDictionary' => 'hipanel:stock',
                'format' => 'raw',
                'value' => fn(array $models): string => $this->stringify(
                    $models,
                    function (array $parts, array &$acc) {
                        $intermediate = [];
                        $brands = array_unique(ArrayHelper::getColumn($parts, 'model_brand_label'));
                        foreach ($brands as $brand) {
                            $intermediate[] = $brand;
                        }
                        $acc[] = implode(', ', $intermediate);
                    },
                    static function ($manufacturer, $_, array &$acc) {
                        $acc[] = $manufacturer;
                    }
                ),
            ],
            'price' => [
                'label' => Yii::t('hipanel.finance.price', 'Price'),
                'attribute' => 'price',
                'visible' => Yii::$app->user->can('order.read'),
                'format' => 'raw',
                'value' => fn(array $models): string => $this->stringify(
                    $models,
                    function (array $parts, array &$acc) {
                        $prices = array_filter(ArrayHelper::map(
                            $parts,
                            'id',
                            fn($p) => empty($p->price) ? null : $this->formater->asCurrency($p->price, $p->currency))
                        );
                        foreach ($prices as $price) {
                            if (array_key_exists($price, $acc)) {
                                $acc[$price]++;
                            } else {
                                $acc[$price] = 1;
                            }
                        }
                    },
                    static function ($count, $price, array &$acc) {
                        $acc[] = sprintf(
                            "%s <span class='text-muted'>x</span> %s",
                            Html::tag('span', $count, ['class' => 'text-navy']),
                            Html::tag('span', $price, ['class' => 'text-fuchsia'])
                        );
                    }
                ),
            ],
            'move_time' => [
                'label' => Yii::t('hipanel', 'Date'),
                'attribute' => 'move_time',
                'format' => 'raw',
                'visible' => Yii::$app->user->can('order.read'),
                'value' => fn(array $models): string => $this->stringify(
                    $models,
                    function (array $parts, array &$acc) {
                        foreach ($parts as $part) {
                            $time = $this->formater->asDate($part->move_time);
                            if (!empty($part->price) && !in_array($time, $acc, true)) {
                                $acc[] = $time;
                            }
                        }
                    },
                    static function ($moveTime, $_, array &$acc) {
                        $acc[] = $moveTime;
                    }
                ),
            ],
            'first_move' => [
                'label' => Yii::t('hipanel:stock', 'Order No.'),
                'format' => 'raw',
                'attribute' => 'first_move',
                'visible' => Yii::$app->user->can('order.read'),
                'contentOptions' => ['style' => ''],
                'value' => fn(array $models): string => $this->stringify(
                    $models,
                    static function (array $parts, array &$acc) {
                        foreach ($parts as $part) {
                            if (!isset($acc[$part->order_id])) {
                                $acc[$part->order_id] = $part->order_name;
                            }
                        }
                    },
                    static function ($orderName, $orderId, array &$acc) {
                        $acc[] = Html::a($orderName, ['@order/view', 'id' => $orderId]);
                    }
                ),
            ],
            'company' => [
                'attribute' => 'company',
                'label' => Yii::t('hipanel:stock', 'Company'),
                'visible' => Yii::$app->user->can('part.create'),
                'format' => 'raw',
                'value' => fn(array $models): string => $this->stringify(
                    $models,
                    static function (array $parts, array &$acc) {
                        $intermediate = [];
                        $companies = array_unique(ArrayHelper::getColumn($parts, 'company'));
                        foreach ($companies as $company) {
                            $intermediate[] = $company;
                        }
                        $acc[] = implode(', ', $intermediate);
                    },
                    static function ($company, $_, &$acc) {
                        $acc[] = $company;
                    }
                ),
            ],
        ];
    }

    /**
     * @param array $bunchOfParts
     * @param Closure $collectorClosure
     * @param Closure $returnClosure
     * @return string
     */
    private function stringify(array $bunchOfParts, Closure $collectorClosure, Closure $returnClosure): string
    {
        $returnAccumulator = [];
        $collectorAccumulator = [];
        foreach ($bunchOfParts as $parts) {
            $collectorClosure($parts, $collectorAccumulator);
        }
        array_walk($collectorAccumulator, static function ($value, $key) use (&$returnAccumulator, $returnClosure) {
            $returnClosure($value, $key, $returnAccumulator);
        });

        return implode('<br>', array_map(static fn(string $value) => Html::tag('nobr', $value), $returnAccumulator));
    }

    private function getModelType(array $models): string
    {
        $parts = reset($models);

        return reset($parts)->model_type;
    }
}
