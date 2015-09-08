<?php

namespace hipanel\modules\stock\controllers;

use hipanel\base\CrudController;
use hipanel\models\Ref;

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
        return Ref::getList('state,domain');
    }

    public function getBrands()
    {
        return Ref::getList('state,domain');
    }

    public function getLocations()
    {
        return Ref::getList('state,domain');
    }
}