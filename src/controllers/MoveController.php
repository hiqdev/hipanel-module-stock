<?php
namespace hipanel\modules\stock\controllers;

use hipanel\base\CrudController;
use hipanel\models\Ref;
use Yii;

class MoveController extends CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => 'hipanel\actions\IndexAction',
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getTypes(),
                        'moveTypes' => $action->controller->getMoveTypes(),
                    ];
                },
            ],
        ];
    }

    public function getTypes()
    {
        return Ref::getList('type,model');
    }

    public function getMoveTypes()
    {
        return Ref::getList('type,move', ['orderby' => 'no_asc', 'with_recursive' => true]);
    }
}