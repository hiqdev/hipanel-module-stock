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
use hipanel\actions\OrientationAction;
use hipanel\actions\PrepareBulkAction;
use hipanel\actions\RedirectAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartPerformAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\models\Ref;
use hipanel\modules\stock\models\Part;
use Yii;
use yii\base\Event;
use yii\helpers\ArrayHelper;

class PartController extends CrudController
{
    public function actions()
    {
        return [
            'update-serial' => [
                'scenario' => 'update-serial',
            ],
            'bulk-set-price' => [
                'class' => PrepareBulkAction::class,
                'scenario' => 'set-price',
                'view' => '_setPrice',
                'data' => function ($action) {
                    return [
                        'currencyTypes' => $action->controller->getCurrencyTypes(),
                    ];
                },
            ],
            'set-price' => [
                'class' => SmartUpdateAction::class,
                'scenario' => 'update',
                'success' => Yii::t('app', 'Price changed'),
                'error' => Yii::t('app', 'Failed change price'),
                'POST html' => [
                    'save'    => true,
                    'success' => [
                        'class' => RedirectAction::class,
                    ],
                ],
                'on beforeSave' => function (Event $event) {
                    /** @var \hipanel\actions\Action $action */
                    $action = $event->sender;
                    $bulkPrice = Yii::$app->request->post('price');
                    $bulkCurrency = Yii::$app->request->post('currency');
                    $action->collection->set(Part::find()->where(['id' => ArrayHelper::getColumn($action->collection->models, 'id')])->all());
                    // TODO: silverfire подумай как переделать
                    foreach ($action->collection->models as $model) {
                        $model->scenario = 'update';
                        $model->price = $bulkPrice;
                        $model->currency = $bulkCurrency;
                    }
                },
            ],
            'index' => [
                'class' => IndexAction::class,
                'view'  => 'index',
                'data'  => function ($action, $data) {
                    $local_sums = [];
                    $total_sums = [];
                    $representation = Yii::$app->request->get('representation');
                    if ($representation === 'report') {
                        foreach ($data['dataProvider']->getModels() as $model) {
                            $local_sums[$model->currency] += $model->price;
                        }
                        $query = $action->parent->dataProvider->query;
                        $query->andWhere(['groupby' => 'total_price']);
                        foreach ($query->all() as $model) {
                            $total_sums[$model->currency] += $model->price;
                        }
                    }

                    return [
                        'total_sums'        => $total_sums,
                        'local_sums'        => $local_sums,
                        'representation'    => $representation,
                        'types'             => $action->controller->getTypes(),
                        'brands'            => $action->controller->getBrands(),
                        'locations'         => $action->controller->getLocations(),
                    ];
                },
            ],
            'view' => [
                'class' => ViewAction::class,
                'on beforePerform' => function (Event $event) {
                    /** @var \hipanel\actions\SearchAction $action */
                    $action = $event->sender;
                    $dataProvider = $action->getDataProvider();
                    $dataProvider->query->joinWith('model');
                },
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
                'data' => function ($action) {
                    return [
                        'currencyTypes' => $action->controller->getCurrencyTypes(),
                    ];
                },
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
            'set-orientation' => [
                'class' => OrientationAction::class,
                'allowedRoutes' => [
                    '@part/index'
                ]
            ]
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
        $query = $this->searchModel()->search([])->query->andWhere(['groupby' => 'place']);
        $res = [];
        foreach ($query->all() as $model) {
            $res[$model->place] = $model->place . '   - ' . Yii::t('app', '{0, plural, one{# item} other{# items}}', $model->count);
        }

        return $res;
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
        return Ref::getList('type,currency', ['orderby' => 'no_asc']);
    }

    public function getRemotehands()
    {
        return Ref::getList('destination,remotehands', ['orderby' => 'name_asc']);
    }
}
