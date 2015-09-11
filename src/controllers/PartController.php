<?php

namespace hipanel\modules\stock\controllers;

use hipanel\base\CrudController;
use hipanel\models\Ref;
use Yii;

class PartController extends CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => 'hipanel\actions\IndexAction',
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getTypes(),
                        'brands' => $action->controller->getBrands(),
                        'locations' => $action->controller->getLocations(),
                    ];
                },
            ],
            'create' => [
                'class' => 'hipanel\actions\SmartCreateAction',
                'success' => Yii::t('app', 'Part was created'),
                'data' => function ($action) {
                    return [
                        'moveTypes' => $action->controller->getMoveTypes(),
                        'suppliers' => $action->controller->getSuppliers(),
                        'currencyTypes' => $action->controller->getCurrencyTypes(),
                    ];
                },
            ],
            'update' => [
                'class' => 'hipanel\actions\SmartUpdateAction',
                'success' => Yii::t('app', 'Part was updated'),
            ],
            'reserve' => [
                'class' => 'hipanel\actions\SmartUpdateAction',
                'success' => Yii::t('app', 'Parts was reserved'),
            ],
            'validate-form' => [
                'class' => 'hipanel\actions\ValidateFormAction',
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
}