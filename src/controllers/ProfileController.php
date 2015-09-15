<?php
namespace hipanel\modules\stock\controllers;

use hipanel\base\CrudController;
use Yii;

class ProfileController extends CrudController
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