<?php
namespace hipanel\modules\stock\controllers;

use hipanel\base\CrudController;
use hipanel\models\Ref;
use Yii;

class ModelController extends CrudController
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
                    ];
                },
            ],
            'create' => [
                'class' => 'hipanel\actions\SmartCreateAction',
                'success' => Yii::t('app', 'Model was created'),
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getTypes(),
                        'brands' => $action->controller->getBrands(),
                    ];
                },
            ],
            'update' => [
                'class' => 'hipanel\actions\SmartUpdateAction',
                'success' => Yii::t('app', 'Model was updated'),
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getTypes(),
                        'brands' => $action->controller->getBrands(),
                    ];
                },
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

    public function getDcs()
    {
        return Ref::getList('type,dc');
    }

    public function getBrands()
    {
        return Ref::getList('type,brand');
    }
}