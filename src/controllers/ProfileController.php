<?php
namespace hipanel\modules\stock\controllers;

use hipanel\actions\IndexAction;
use hipanel\base\CrudController;
use Yii;

class ProfileController extends CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
            ],
        ];
    }
}