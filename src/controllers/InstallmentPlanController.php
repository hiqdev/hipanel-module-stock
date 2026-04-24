<?php
/**
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2026, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\stock\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartPerformAction;
use hipanel\actions\ViewAction;
use hipanel\filters\EasyAccessControl;
use hipanel\modules\stock\actions\InstallmentPlanCreateBillAction;
use hipanel\modules\stock\actions\InstallmentPlanProcessAction;
use Yii;
use yii\data\ArrayDataProvider;

class InstallmentPlanController extends \hipanel\base\CrudController
{
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    'delete'       => 'installment-plan.delete',
                    'restore'      => 'installment-plan.restore',
                    'process'      => 'installment-plan.process',
                    'create-bill'  => 'bill.create',
                    '*'            => 'sale.read',
                ],
            ],
        ]);
    }

    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'index' => [
                'class' => IndexAction::class,
            ],
            'delete' => [
                'class' => SmartDeleteAction::class,
                'success' => Yii::t('hipanel:stock', 'Installment plan has been deleted'),
                'error' => Yii::t('hipanel:stock', 'An error occurred when trying to delete installment plan'),
            ],
            'restore' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:stock', 'Installment plan has been restored'),
            ],
            'view' => [
                'class' => ViewAction::class,
                'findOptions' => [
                    'with_items' => 1,
                    'with_all_states' => 1,
                ],
                'data' => function ($action) {
                    $model = $action->getModel();

                    return [
                        'itemsDataProvider' => new ArrayDataProvider([
                            'allModels' => $model->getItems(),
                            'pagination' => false,
                        ]),
                    ];
                },
            ],
            'process' => [
                'class' => InstallmentPlanProcessAction::class,
            ],
            'create-bill' => [
                'class' => InstallmentPlanCreateBillAction::class,
            ],
        ]);
    }
}
