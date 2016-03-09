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
use hipanel\actions\SmartPerformAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\models\Ref;
use hipanel\modules\stock\models\Part;
use hipanel\modules\stock\models\PartSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class PartController extends CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'view'  => 'index',
                'data'  => function ($action, $data) {
                    foreach ($data['dataProvider']->getModels() as $model) {
                        $local_sums[$model->currency] += $model->price;
                    }
                    $query = $action->parent->dataProvider->query;
                    $query->andWhere(['groupby' => 'total_price']);
                    foreach ($query->all() as $model) {
                        $total_sums[$model->currency] += $model->price;
                    }
                    return [
                        'total_sums' => $total_sums,
                        'local_sums' => $local_sums,
                        'types' => $action->controller->getTypes(),
                        'brands' => $action->controller->getBrands(),
                        'locations' => $action->controller->getLocations(),
                        'representation' => Yii::$app->request->get('representation'),
                    ];
                },
            ],
            'view' => [
                'class' => ViewAction::class,
//                'findOptions' => ['with_dns' => 1],
//                'data' => function ($action) {
//                    return [
//                        'domainContactInfo' => Domain::perform('GetContactsInfo', ['id' => $action->getId()]),
//                        'pincodeModel' => new DynamicModel(['pincode']),
//                    ];
//                },
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'success' => Yii::t('app', 'Part were created'),
                'data' => function ($action) {
                    return [
                        'moveTypes' => $action->controller->getMoveTypes(),
                        'suppliers' => $action->controller->getSuppliers(),
                        'currencyTypes' => $action->controller->getCurrencyTypes(),
                    ];
                },
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('app', 'Part were updated'),
            ],
            'reserve' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('app', 'Parts were reserved'),
            ],
            'unreserve' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('app', 'Parts were unreserved'),
            ],
            'move' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('app', 'Parts were moved'),
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getMoveTypes(),
                        'remotehands' => $action->controller->getRemotehands(),
                    ];
                },
            ],
            'bulk-move' => [
                'class' => SmartCreateAction::class,
                'success' => Yii::t('app', 'Parts were moved'),
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getMoveTypes(),
                        'remotehands' => $action->controller->getRemotehands(),
                    ];
                },
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
        ];
    }

    /**
     * @param integer $id
     * @return string
     */
    public function actionRenderObjectParts($id)
    {
        $parts = Part::find(['dst_id' => $id])->all();
        $data = ArrayHelper::index($parts, 'id', ['model_type_label', 'model_id']);
        return $this->renderAjax('_objectParts', ['data' => $data]);
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
            'reserve' => Yii::t('app', 'Reserve'),
            'stock' => Yii::t('app', 'Stock'),
            'rma' => Yii::t('app', 'RMA'),
            'trash' => Yii::t('app', 'Trash'),
        ];
    }

    public function getMoveTypes()
    {
        return Ref::getList('type,move', ['orderby' => 'no_asc', 'with_recursive' => true]);
    }

    public function getSuppliers()
    {
        return Ref::getList('destination,supplier', ['orderby' => 'name_asc']);
    }

    public function getCurrencyTypes()
    {
        return Ref::getList('type,currency', ['orderby' => 'name_asc']);
    }

    public function getRemotehands()
    {
        return Ref::getList('destination,remotehands', ['orderby' => 'name_asc']);
    }
}
