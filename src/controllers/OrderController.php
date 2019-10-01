<?php
/**
 * hipanel.advancedhosters.com
 *
 * @link      http://hipanel.advancedhosters.com/
 * @package   hipanel.advancedhosters.com
 * @license   proprietary
 * @copyright Copyright (c) 2016-2019, AdvancedHosters (https://advancedhosters.com/)
 */

namespace hipanel\modules\stock\controllers;

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
                'data' => function ($action, $data) {
                    $local_sums = [];
                    $total_sums = [];
                    $representation = $this->indexPageUiOptionsModel->representation;
                    if ($representation === 'profit-report') {
                        foreach ($data['dataProvider']->getModels() as $model) {
                            $local_sums[$model->profit->currency] += $model->profit->total;
                        }
//                        $query = $action->parent->dataProvider->query;
//                        $query->andWhere(['groupby' => 'total_price']);
//                        foreach ($query->all() as $model) {
//                            $total_sums[$model->profit->currency] += $model->profit->total;
//                        }
                    }
                    return [
                        'local_sums' => array_filter($local_sums),
                        'total_sums' => array_filter($total_sums),
                    ];
                },
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'success' => Yii::t('hipanel.stock.order', 'Order has been created'),
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel.stock.order', 'Order has been updated'),
            ],
            'view' => [
                'class' => ViewAction::class,
            ],
            'delete' => [
                'class' => SmartDeleteAction::class,
                'success' => Yii::t('hipanel.stock.order', 'Order has been deleted'),
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
            'profit-view' => [
                'view'  => 'profit-view',
                'class' => ViewAction::class,
                'on beforePerform' => function (Event $event) {
                    $event->sender->getDataProvider()->query->withProfitParts();
                },
                'data' => function ($action, $data) {
                    $local_sums = [];
                    $total_sums = [];
                    foreach ($data['model']->profitParts as $profitPart) {
                        $local_sums[$profitPart->currency] += $profitPart->total;
                    }
//                        $query = $action->parent->dataProvider->query;
//                        $query->andWhere(['groupby' => 'total_price']);
//                        foreach ($query->all() as $model) {
//                            $total_sums[$model->profit->currency] += $model->profit->total;
//                        }
                    return [
                        'local_sums' => array_filter($local_sums),
                        'total_sums' => array_filter($total_sums),
                    ];
                },
            ],
        ]);
    }
}
