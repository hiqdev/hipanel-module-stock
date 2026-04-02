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
use hipanel\actions\ViewAction;
use hipanel\filters\EasyAccessControl;
use yii\data\ArrayDataProvider;

class InstallmentPlanController extends \hipanel\base\CrudController
{
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    '*' => 'sale.read',
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
            'view' => [
                'class' => ViewAction::class,
                'findOptions' => ['with_items' => 1],
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
        ]);
    }
}
