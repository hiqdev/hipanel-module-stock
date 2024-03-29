<?php

namespace hipanel\modules\stock\controllers;

use hipanel\actions\ComboSearchAction;
use hipanel\actions\IndexAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\filters\EasyAccessControl;
use Yii;
use yii\base\Event;

class OrderController extends CrudController
{
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    'create' => 'order.create',
                    'update' => 'order.update',
                    'delete' => 'order.delete',
                    'profit-view' => 'order.read-profits',

                    '*' => 'order.read',
                ],
            ],
        ]);
    }

    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'index' => [
                'class' => IndexAction::class,
                'on beforePerform' => function (Event $event) {
                    $query = $event->sender->getDataProvider()->query->withParts();
                    if ($event->sender->controller->indexPageUiOptionsModel->representation === 'profit-report') {
                        $query->withProfit();
                    }
                },
            ],
            'search' => [
                'class' => ComboSearchAction::class,
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'success' => Yii::t('hipanel.stock.order', 'Order has been created'),
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel.stock.order', 'Order has been updated'),
                'on beforeFetch' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    $dataProvider = $action->getDataProvider();
                    $dataProvider->query->withFiles();
                },
            ],
            'view' => [
                'class' => ViewAction::class,
                'on beforePerform' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    $dataProvider = $action->getDataProvider();
                    $dataProvider->query->withFiles();
                },
            ],
            'delete' => [
                'class' => SmartDeleteAction::class,
                'success' => Yii::t('hipanel.stock.order', 'Order has been deleted'),
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
                'validatedInputId' => false,
            ],
            'profit-view' => [
                'view'  => 'profit-view',
                'class' => ViewAction::class,
                'on beforePerform' => function (Event $event) {
                    $event->sender->getDataProvider()->query->withPartsProfit();
                },
            ],
        ]);
    }
}
