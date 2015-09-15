<?php
namespace hipanel\modules\stock\controllers;

use hipanel\base\CrudController;
use Yii;

class UsertagController extends CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => 'hipanel\actions\IndexAction',
            ],
        ];
    }
}