<?php

/*
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\stock\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\OrientationAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartPerformAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\models\Ref;
use hipanel\modules\stock\models\Model;
use Yii;

class ModelController extends CrudController
{
    public function actions()
    {
        return [
            'set-orientation' => [
                'class' => OrientationAction::class,
                'allowedRoutes' => [
                    '@model/index'
                ]
            ],
            'index' => [
                'class' => IndexAction::class, // with_counters
                'findOptions' => ['with_counters' => 1],
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getTypes(),
                        'brands' => $action->controller->getBrands(),
                    ];
                },
            ],
            'view' => [
                'class' => ViewAction::class,
                'findOptions' => ['with_counters' => 1],
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'success' => Yii::t('app', 'Model was created'),
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getTypes(),
                        'brands' => $action->controller->getBrands(),
                    ];
                },
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('app', 'Model was updated'),
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getTypes(),
                        'brands' => $action->controller->getBrands(),
                    ];
                },
            ],
            'mark-hidden-from-user' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('app', 'Models marked'),
            ],
            'unmark-hidden-from-user' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('app', 'Models marked'),
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
        ];
    }

    public function actionSubform()
    {
        $subFormName = Yii::$app->request->post('subFormName');
        $itemNumber = Yii::$app->request->post('itemNumber');
        if ($subFormName) {
            $validFormNames = $this->getCustomType();
            if (in_array($subFormName, $validFormNames, true)) {
                return $this->renderAjax('_' . $subFormName, ['model' => new Model(), 'i' => $itemNumber]);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    public function getTypes()
    {
        return Ref::getList('type,model');
    }

    public function getDcs()
    {
        return Ref::getList('type,dc');
    }

    public function getBrands()
    {
        return Ref::getList('type,brand');
    }

    public function getCustomType()
    {
        return ['server', 'chassis', 'motherboard', 'ram', 'hdd', 'cpu'];
    }
}
