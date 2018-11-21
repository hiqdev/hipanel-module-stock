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
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\filters\EasyAccessControl;
use Yii;

/**
 * Class ModelGroupController
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class ModelGroupController extends CrudController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    'create' => 'model.create',
                    'update' => 'model.update',
                    'delete' => 'model.delete',
                    'copy' => 'model.create',
                    '*' => 'model.read',
                ],
            ],
        ]);
    }

    public function actions()
    {
        return array_merge(parent::actions(), [
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
            'index' => [
                'class' => IndexAction::class,
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'success' => Yii::t('hipanel:stock', 'Created'),
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:stock', 'Updated'),
            ],
            'copy' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:stock', 'Copied'),
            ],
            'delete' => [
                'class' => SmartDeleteAction::class,
                'success' => Yii::t('hipanel:stock', 'Deleted'),
            ],
            'view' => [
                'class' => ViewAction::class,
            ],
        ]);
    }
}
