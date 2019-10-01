<?php
/**
 * hipanel.advancedhosters.com
 *
 * @link      http://hipanel.advancedhosters.com/
 * @package   hipanel.advancedhosters.com
 * @license   proprietary
 * @copyright Copyright (c) 2016-2019, AdvancedHosters (https://advancedhosters.com/)
 */

namespace hipanel\modules\stock\grid;

use hipanel\grid\BoxedGridView;
use hipanel\grid\RefColumn;
use hipanel\modules\stock\controllers\PartController;
use hipanel\modules\stock\helpers\ProfitRepresentations;
use hipanel\modules\stock\menus\OrderActionsMenu;
use hipanel\modules\stock\models\Order;
use hipanel\modules\stock\models\OrderSearch;
use hipanel\modules\stock\models\Part;
use hipanel\modules\stock\widgets\combo\ContactCombo;
use hipanel\modules\stock\widgets\OrderState;
use hipanel\modules\stock\widgets\OrderType;
use hipanel\widgets\ArraySpoiler;
use hiqdev\higrid\DataColumn;
use hiqdev\yii2\menus\grid\MenuColumn;
use Yii;
use yii\bootstrap\Html;
use yii\helpers\Url;
use hipanel\grid\MainColumn;

class OrderGridView extends BoxedGridView
{
    /**
     * @return array
     */
    private function getProfitColumns(): array
    {
        return ProfitRepresentations::getColumns(function ($attr, $cur): array {
            $valueArray = [
                'value' => function (Order $order) use ($attr, $cur): string {
                    if ($order->profit->currency === $cur) {
                        return "<div class='right-aligned'>{$order->profit->{$attr}}</div>";
                    }
                    return '';
                },
                'format' => 'raw',
            ];
            if ($this->showFooter) {
                $models = $this->dataProvider->getModels();
                $valueArray['footer'] = (function () use ($attr, $cur, $models): string {
                    $sum = array_reduce($models, function (?float $sum, Order $order) use ($attr, $cur): ?float {
                        if ($order->profit && $order->profit->currency === $cur) {
                            return $sum + $order->profit->{$attr};
                        }
                        return $sum;
                    }, null);
                    $res = empty($sum) ? '' : number_format($sum, 2);
                    return "<div class='right-aligned'>$res</div>";
                })();
            }
            return [
                'key' => "{$attr}_{$cur}",
                'value' => $valueArray,
            ];
        });
    }

    /**
     * @inheritdoc
     */
    public function columns()
    {
        return array_merge(parent::columns(), $this->getProfitColumns(), [
            'comment_profit' => [
                'format' => 'raw',
                'label' => 'Order No',
                'filterAttribute' => 'comment_ilike',
                'value' => function (Order $order): string {
                    return Html::a($order->comment, ['profit-view', 'id' => $order->id], ['class' => 'bold']);
                },
                'footer' => '<b>TOTAL on screen</b>',
            ],
            'actions' => [
                'class' => MenuColumn::class,
                'menuClass' => OrderActionsMenu::class,
            ],
            'comment' => [
                'class' => MainColumn::class,
                'filterAttribute' => 'comment_ilike',
            ],
            'time' => [
                'attribute' => 'time',
                'filter' => false,
                'format' => 'date',
                'contentOptions' => ['style' => 'white-space:nowrap'],
            ],
            'seller' => [
                'attribute' => 'seller_id',
                'filterAttribute' => 'seller_id',
                'format' => 'raw',
                'filter' => function (DataColumn $column, OrderSearch $model, string $attribute) {
                    return ContactCombo::widget([
                        'model' => $model,
                        'attribute' => $attribute,
                        'formElementSelector' => 'td',
                    ]);
                },
                'value' => function (Order $model) {
                    return Html::a($model->seller, ['@client/view', 'id' => $model->seller_id]);
                }
            ],
            'buyer' => [
                'attribute' => 'buyer_id',
                'filterAttribute' => 'buyer_id',
                'format' => 'raw',
                'filter' => function (DataColumn $column, OrderSearch $model, string $attribute) {
                    return ContactCombo::widget([
                        'model' => $model,
                        'attribute' => $attribute,
                        'formElementSelector' => 'td',
                    ]);
                },
                'value' => function (Order $model) {
                    return Html::a($model->buyer, ['@client/view', 'id' => $model->buyer_id]);
                }
            ],
            'type' => [
                'filterOptions' => ['class' => 'narrow-filter'],
                'class' => RefColumn::class,
                'format' => 'raw',
                'gtype' => 'type,zorder',
                'i18nDictionary' => 'hipanel.stock.order',
                'value' => function (Order $model) {
                    return OrderType::widget(compact('model'));
                },
            ],
            'state' => [
                'filterOptions' => ['class' => 'narrow-filter'],
                'class' => RefColumn::class,
                'format' => 'raw',
                'gtype' => 'state,zorder',
                'i18nDictionary' => 'hipanel.stock.order',
                'value' => function (Order $model) {
                    return OrderState::widget(compact('model'));
                },
            ],
            'no' => [
                'filterAttribute' => 'no_ilike',
            ],
            'parts' => [
                'format' => 'raw',
                'filter' => false,
                'contentOptions' => [
                    'class' => 'text-right',
                    'style' => 'width:1%; white-space:nowrap;',
                ],
                'label' => Yii::t('hipanel.stock.order', 'Parts'),
                'value' => function (Order $order) {
                    return ArraySpoiler::widget([
                        'data' => $order->parts,
                        'delimiter' => '<br />',
                        'visibleCount' => 0,
                        'formatter' => function (Part $part, $idx) use ($order) {
                            return Html::a($part->title, Url::toRoute(['@part/view', 'id' => $part->id]), [
                                'class' => 'text-bold',
                                'target' => '_blank',
                            ]);
                        },
                        'button' => [
                            'label' => count($order->parts),
                            'tag' => 'button',
                            'type' => 'button',
                            'class' => 'btn btn-xs btn-flat',
                            'style' => 'font-size: 10px',
                            'popoverOptions' => [
                                'html' => true,
                                'placement' => 'bottom',
                                'title' => Html::a(Yii::t('hipanel.stock.order', 'Show all parts'), PartController::getSearchUrl(['order_id' => $order->id])),
                                'template' => '
                                    <div class="popover" role="tooltip">
                                        <div class="arrow"></div>
                                        <h3 class="popover-title"></h3>
                                        <div class="popover-content" style="min-width: 15rem; height: 15rem; overflow-x: scroll;"></div>
                                    </div>
                                ',
                            ],
                        ],
                    ]);
                }
            ],
        ]);
    }
}
