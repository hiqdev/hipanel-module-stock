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
            'reserve'   =>  Yii::t('app', 'Reserve'),
            'stock'     =>  Yii::t('app', 'Stock'),
            'rma'       =>  Yii::t('app', 'RMA'),
            'trash'     =>  Yii::t('app', 'Trash'),
        ];
    }
}