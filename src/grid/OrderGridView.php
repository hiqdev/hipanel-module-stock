<?php

namespace hipanel\modules\stock\grid;

use hipanel\grid\BoxedGridView;
use hipanel\grid\RefColumn;
use hipanel\modules\stock\menus\OrderActionsMenu;
use hipanel\modules\stock\widgets\combo\ContactCombo;
use hipanel\modules\stock\widgets\OrderState;
use hipanel\modules\stock\widgets\OrderType;
use hiqdev\yii2\menus\grid\MenuColumn;
use yii\bootstrap\Html;

class OrderGridView extends BoxedGridView
{
    /**
     * @inheritdoc
     */
    public function columns()
    {
        return array_merge(parent::columns(), [
            'actions' => [
                'class' => MenuColumn::class,
                'menuClass' => OrderActionsMenu::class,
            ],
            'time' => [
                'attribute' => 'time',
                'filter'    => false,
                'format'    => 'date',
                'contentOptions' => ['style' => 'white-space:nowrap'],
            ],
            'seller' => [
                'attribute' => 'seller_id',
                'filterAttribute' => 'seller_id',
                'format'    => 'raw',
                'filter' => function ($column, $model, $attribute) {
                    return ContactCombo::widget([
                        'model' => $model,
                        'attribute' => $attribute,
                        'formElementSelector' => 'td',
                    ]);
                },
                'value' => function ($model) {
                    return Html::a($model->seller, ['@client/view', 'id' => $model->seller_id]);
                }
            ],
            'buyer' => [
                'attribute' => 'buyer_id',
                'filterAttribute' => 'buyer_id',
                'format'    => 'raw',
                'filter' => function ($column, $model, $attribute) {
                    return ContactCombo::widget([
                        'model' => $model,
                        'attribute' => $attribute,
                        'formElementSelector' => 'td',
                    ]);
                },
                'value' => function ($model) {
                    return Html::a($model->buyer, ['@client/view', 'id' => $model->buyer_id]);
                }
            ],
            'type' => [
                'filterOptions' => ['class' => 'narrow-filter'],
                'class' => RefColumn::class,
                'format'    => 'raw',
                'gtype' => 'type,zorder',
                'i18nDictionary' => 'hipanel.stock.order',
                'value' => function ($model) {
                    return OrderType::widget(compact('model'));
                },
            ],
            'state' => [
                'filterOptions' => ['class' => 'narrow-filter'],
                'class' => RefColumn::class,
                'format'    => 'raw',
                'gtype' => 'state,zorder',
                'i18nDictionary' => 'hipanel.stock.order',
                'value' => function ($model) {
                    return OrderState::widget(compact('model'));
                },
            ],
            'no' => [
                'filterAttribute' => 'no_ilike',
            ],
            'comment' => [
                'filterAttribute' => 'comment_ilike',
            ],
        ]);
    }
}