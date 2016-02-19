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
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartPerformAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\models\Ref;
use Yii;

class PartController extends CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getTypes(),
                        'brands' => $action->controller->getBrands(),
                        'locations' => $action->controller->getLocations(),
                    ];
                },
            ],
            'view' => [
                'class' => ViewAction::class,
//                'findOptions' => ['with_dns' => 1],
//                'data' => function ($action) {
//                    return [
//                        'domainContactInfo' => Domain::perform('GetContactsInfo', ['id' => $action->getId()]),
//                        'pincodeModel' => new DynamicModel(['pincode']),
//                    ];
//                },
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'success' => Yii::t('app', 'Part were created'),
                'data' => function ($action) {
                    return [
                        'moveTypes' => $action->controller->getMoveTypes(),
                        'suppliers' => $action->controller->getSuppliers(),
                        'currencyTypes' => $action->controller->getCurrencyTypes(),
                    ];
                },
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('app', 'Part were updated'),
            ],
            'reserve' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('app', 'Parts were reserved'),
            ],
            'unreserve' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('app', 'Parts were unreserved'),
            ],
            'move' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('app', 'Parts were moved'),
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getMoveTypes(),
                        'remotehands' => $action->controller->getRemotehands(),
                    ];
                },
            ],
            'bulk-move' => [
                'class' => SmartCreateAction::class,
                'success' => Yii::t('app', 'Parts were moved'),
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getMoveTypes(),
                        'remotehands' => $action->controller->getRemotehands(),
                    ];
                },
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
        ];
    }

    public function getTypes()
    {
        return Ref::getList('type,model');
    }

    public function getBrands()
    {
        return Ref::getList('type,brand');
    }

    public function getLocations()
    {
        return [
            'reserve' => Yii::t('app', 'Reserve'),
            'stock' => Yii::t('app', 'Stock'),
            'rma' => Yii::t('app', 'RMA'),
            'trash' => Yii::t('app', 'Trash'),
        ];
    }

    public function getMoveTypes()
    {
        return Ref::getList('type,move', ['orderby' => 'no_asc', 'with_recursive' => true]);
    }

    public function getSuppliers()
    {
        return Ref::getList('destination,supplier', ['orderby' => 'name_asc']);
    }

    public function getCurrencyTypes()
    {
        return Ref::getList('type,currency', ['orderby' => 'name_asc']);
    }

    public function getRemotehands()
    {
        return Ref::getList('destination,remotehands', ['orderby' => 'name_asc']);
    }
}
