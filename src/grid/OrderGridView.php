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
use hipanel\modules\stock\menus\OrderActionsMenu;
use hipanel\modules\stock\models\Order;
use hipanel\modules\stock\models\OrderSearch;
use hipanel\modules\stock\widgets\combo\ContactCombo;
use hipanel\modules\stock\widgets\OrderState;
use hipanel\modules\stock\widgets\OrderType;
use hiqdev\higrid\DataColumn;
use hiqdev\yii2\menus\grid\MenuColumn;
use yii\bootstrap\Html;
use yii\grid\Column;

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
                'value' => function ($model) {
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
            'comment' => [
                'filterAttribute' => 'comment_ilike',
            ],
        ]);
    }
}
