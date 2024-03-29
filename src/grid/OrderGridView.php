<?php

namespace hipanel\modules\stock\grid;

use hipanel\grid\BoxedGridView;
use hipanel\grid\RefColumn;
use hipanel\modules\stock\controllers\PartController;
use hipanel\modules\stock\helpers\ProfitColumns;
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
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $this->options['class'] .= ' order-grid-view';
        $this->view->registerCss("
            .order-grid-view .popover .popover-content {
                max-height: 170px;
                overflow-y: scroll;
            }
        ");
    }

    /**
     * @return array
     */
    private function getProfitColumns(): array
    {
        return ProfitColumns::getGridColumns($this, 'order_id');
    }

    /**
     * @inheritdoc
     */
    public function columns()
    {
        return array_merge(parent::columns(), $this->getProfitColumns(), [
            'name_profit' => [
                'format' => 'raw',
                'label' => Yii::t('hipanel:stock', 'Order No.'),
                'filterAttribute' => 'name_ilike',
                'value' => function (Order $order): string {
                    return Html::a(Html::encode($order->name), ['profit-view', 'id' => $order->id], ['class' => 'bold']);
                },
                'footer' => '<b>' . Yii::t('hipanel:stock', 'TOTAL on screen') . '</b>',
            ],
            'actions' => [
                'class' => MenuColumn::class,
                'menuClass' => OrderActionsMenu::class,
            ],
            'name' => [
                'class' => MainColumn::class,
                'filterAttribute' => 'name_ilike',
            ],
            'company_id' => [
                'class' => CompanyColumn::class,
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
                    return Html::a(Html::encode($model->seller), ['@client/view', 'id' => $model->seller_id]);
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
                    return Html::a(Html::encode($model->buyer), ['@client/view', 'id' => $model->buyer_id]);
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
                    'class' => 'text-center',
                    'style' => 'width:1%; white-space:nowrap; vertical-align: middle;',
                ],
                'label' => Yii::t('hipanel.stock.order', 'Parts'),
                'value' => function (Order $order) {
                    return ArraySpoiler::widget([
                        'data' => $order->parts,
                        'delimiter' => '<br />',
                        'visibleCount' => 0,
                        'formatter' => function (Part $part, $idx) use ($order) {
                            return Html::a(Yii::t('hipanel.stock.order', Html::encode($part->title)), Url::toRoute(['@part/view', 'id' => $part->id]), [
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
