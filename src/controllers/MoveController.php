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

use hipanel\actions\Action;
use hipanel\actions\IndexAction;
use hipanel\actions\OrientationAction;
use hipanel\actions\SearchAction;
use hipanel\actions\SmartPerformAction;
use hipanel\base\CrudController;
use hipanel\models\Ref;
use Yii;

class MoveController extends CrudController
{
    public function actions()
    {
        return [
            'set-orientation' => [
                'class' => OrientationAction::class,
                'allowedRoutes' => [
                    '@move/index'
                ]
            ],
            'index' => [
                'class' => IndexAction::class,
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getTypes(),
                        'moveTypes' => $action->controller->getMoveTypes(),
                    ];
                },
            ],
            'directions-list' => [
                'class' => SearchAction::class,
                'on beforeSave' => function ($event) {
                    /** @var Action $action */
                    $action = $event->sender;
                    $action->dataProvider->query->options['scenario'] = 'get-directions';
                },
            ],
            'delete' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('app', 'Deleted'),
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
