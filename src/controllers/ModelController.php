<?php
namespace hipanel\modules\stock\controllers;

use hipanel\base\CrudController;
use hipanel\models\Ref;
use hipanel\modules\stock\models\Model;
use Yii;

class ModelController extends CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => 'hipanel\actions\IndexAction', // with_counters
                'findOptions' => ['with_counters' => 1],
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getTypes(),
                        'brands' => $action->controller->getBrands(),
                    ];
                },
            ],
            'view' => [
                'class' => 'hipanel\actions\ViewAction',
                'findOptions' => ['with_counters' => 1],
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
            'mark-hidden-from-user' => [
                'class' => 'hipanel\actions\SmartPerformAction',
                'success' => Yii::t('app', 'Models marked'),
            ],
            'un-mark-hidden-from-user' => [
                'class' => 'hipanel\actions\SmartPerformAction',
                'success' => Yii::t('app', 'Models marked'),
            ],
            'validate-form' => [
                'class' => 'hipanel\actions\ValidateFormAction',
            ],
        ];
    }

    public function actionSubform()
    {
        $subFormName = Yii::$app->request->post('subFormName');
        $itemNumber = Yii::$app->request->post('itemNumber');
        if ($subFormName) {
            $validFormNames = $this->getCustomType();
            if (in_array($subFormName, $validFormNames)) {
                return $this->renderAjax('_' . $subFormName, ['model' => new Model(), 'i' => $itemNumber]);
            } else
                return '';
        } else
            return '';

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