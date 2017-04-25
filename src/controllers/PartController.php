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
use hipanel\actions\PrepareBulkAction;
use hipanel\actions\RedirectAction;
use hipanel\actions\RenderAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartPerformAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\models\Ref;
use hipanel\modules\stock\models\MoveSearch;
use hipanel\modules\stock\models\Part;
use Yii;
use yii\base\DynamicModel;
use yii\base\Event;
use yii\helpers\ArrayHelper;

class PartController extends CrudController
{
    public function actions()
    {
        return [
            'bulk-set-serial' => [
                'class' => PrepareBulkAction::class,
                'view' => '_setSerial',
                'scenario' => 'set-serial',
            ],
            'set-serial' => [
                'class' => SmartPerformAction::class,
                'scenario' => 'set-serial',
                'success' => Yii::t('hipanel:stock', 'Serial updated'),
                'error' => Yii::t('hipanel:stock', 'Failed set serial'),
                'POST html | POST ajax' => [
                    'save' => true,
                    'success' => [
                        'class' => RedirectAction::class
                    ]
                ],
            ],
            'bulk-set-price' => [
                'class' => PrepareBulkAction::class,
                'scenario' => 'set-price',
                'view' => '_setPrice',
            ],
            'set-price' => [
                'class' => SmartUpdateAction::class,
                'scenario' => 'update',
                'success' => Yii::t('hipanel:stock', 'Price changed'),
                'POST html' => [
                    'save' => true,
                    'success' => [
                        'class' => RedirectAction::class,
                    ],
                ],
                'on beforeSave' => function (Event $event) {
                    /** @var \hipanel\actions\Action $action */
                    $action = $event->sender;
                    $data = Yii::$app->request->post('Part');
                    $bulkPrice = $data['price'];
                    $bulkCurrency = $data['currency'];
                    unset($data['price'], $data['currency']);
                    $action->collection->set(Part::find()->where(['id' => ArrayHelper::getColumn($data, 'id')])->all());
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
                'view' => 'index',
                'data' => function ($action, $data) {
                    $local_sums = [];
                    $total_sums = [];
                    $representation = $this->indexPageUiOptionsModel->representation;
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
                        'total_sums' => $total_sums,
                        'local_sums' => $local_sums,
                        'representation' => $representation,
                        'types' => $action->controller->getTypes(),
                        'brands' => $action->controller->getBrands(),
                        'locations' => $action->controller->getLocations(),
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
                'data' => function ($action) {
                    $moveSearch = new MoveSearch();
                    $moveDataProvider = $moveSearch->search([$moveSearch->formName() => [
                        'part_ids' => $action->getId(),
                        'with_parts' => 1,
                    ]]);

                    return [
                        'moveDataProvider' => $moveDataProvider
                    ];
                },
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'success' => Yii::t('hipanel:stock', 'Part has been created'),
                'data' => function ($action) {
                    return [
                        'moveTypes' => $action->controller->getMoveTypes(),
                        'suppliers' => $action->controller->getSuppliers(),
                        'currencyTypes' => $action->controller->getCurrencyTypes(),
                    ];
                },
            ],
            'repair' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:stock', 'Parts have been moved'),
                'data' => function ($action) {
                    return [
                        'moveTypes' => $action->controller->getMoveTypes(),
                        'suppliers' => $action->controller->getSuppliers(),
                        'currencyTypes' => $action->controller->getCurrencyTypes(),
                    ];
                },
            ],
            'copy' => [
                'class' => SmartUpdateAction::class,
                'scenario' => 'copy',
                'success' => Yii::t('hipanel', 'Parts have been copied'),
                'GET html | POST selection' => [
                    'class' => RenderAction::class,
                    'data' => function ($action, $originalData) {
                        return call_user_func($action->parent->data, $action, $originalData);
                    },
                    'params' => function ($action) {
                        $models = $action->parent->fetchModels();
                        foreach ($models as $model) {
                            $model->scenario = 'copy';
                            $model->serial = $model->id = null;
                        }
                        return [
                            'models' => $models,
                            'model' => reset($models),
                        ];
                    },
                ],
                'data' => function ($action) {
                    return [
                        'moveTypes' => $action->controller->getMoveTypes(),
                        'suppliers' => $action->controller->getSuppliers(),
                        'currencyTypes' => $action->controller->getCurrencyTypes(),
                    ];
                },
            ],
            'trash' => [
                'class' => SmartUpdateAction::class,
                'scenario' => 'trash',
                'success' => Yii::t('hipanel:stock', 'Parts have been moved'),
                'data' => function ($action) {
                    return [
                        'moveTypes' => $action->controller->getMoveTypes(),
                        'suppliers' => $action->controller->getSuppliers(),
                        'currencyTypes' => $action->controller->getCurrencyTypes(),
                        'trashId' => $action->controller->getTrashId(),
                    ];
                },
            ],
            'replace' => [
                'class' => SmartUpdateAction::class,
                'scenario' => 'replace',
                'success' => Yii::t('hipanel:stock', 'Parts have been moved'),
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
                'success' => Yii::t('hipanel:stock', 'Part has been updated'),
                'data' => function ($action) {
                    return [
                        'currencyTypes' => $action->controller->getCurrencyTypes(),
                    ];
                },
            ],
            'reserve' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:stock', 'Parts have been updated'),
            ],
            'unreserve' => [
                'class' => SmartUpdateAction::class,
                'view' => 'reserve',
                'success' => Yii::t('hipanel:stock', 'Parts have been updated'),
            ],
            'move-by-one' => [
                'class' => SmartUpdateAction::class,
                'scenario' => 'move-by-one',
                'success' => Yii::t('hipanel:stock', 'Parts have been moved'),
                'view' => 'moveByOne',
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getMoveTypes(),
                        'remotehands' => $action->controller->getRemotehands(),
                    ];
                },
            ],
            'move' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:stock', 'Parts have been moved'),
                'GET html | POST selection' => [
                    'class' => RenderAction::class,
                    'data' => function ($action, $originalData) {
                        return call_user_func($action->parent->data, $action, $originalData);
                    },
                    'params' => function ($action) {
                        $groupedModels = [];
                        $models = $action->parent->fetchModels();
                        $groupBy = Yii::$app->request->get('groupBy');
                        $groupModel = new DynamicModel(compact('groupBy'));
                        $groupModel->addRule('groupBy', 'integer');
                        $groupModel->addRule('groupBy', 'in', ['range' => [2, 4, 6, 8, 12]]);
                        foreach ($models as $model) {
                            $model->scenario = 'move';
                            $model->src_id = $model->dst_id;
                            $model->dst_id = null;
                        }
                        $models = ArrayHelper::index($models, 'id', ['src_id']);
                        if ($groupBy !== null && $groupModel->validate()) {
                            foreach ($models as $src_id => $group) {
                                $groupedModels[$src_id] = array_chunk($group, $groupBy, true);
                            }
                        }

                        return [
                            'models' => $models,
                            'groupedModels' => $groupedModels,
                        ];
                    },
                ],
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getMoveTypes(),
                        'remotehands' => $action->controller->getRemotehands(),
                    ];
                },
                'on beforeSave' => function (Event $event) {
                    /** @var \hipanel\actions\Action $action */
                    $action = $event->sender;
                    $data = [];
                    $partGroups = Yii::$app->request->post('Part');
                    foreach ($partGroups as $src_id => $partGroup) {
                        $groupIds = ArrayHelper::remove($partGroup, 'id');
                        foreach ($groupIds as $id) {
                            $partGroup['id'] = $id;
                            $data[$id] = $partGroup;
                        }
                    }
                    $action->collection->setModel($this->newModel(['scenario' => 'move']));
                    $action->collection->load($data);
                }
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
        $parts = Part::find()->where(['dst_id' => $id])->all();
        $data = ArrayHelper::index($parts, 'id', ['model_type_label', 'model_id']);
        return $this->renderAjax('_objectParts', ['data' => $data]);
    }

    public function getTypes()
    {
        return $this->getRefs('type,model', 'hipanel:stock');
    }

    public function getBrands()
    {
        return $this->getRefs('type,brand', 'hipanel:stock');
    }

    public function getLocations()
    {
        $query = $this->searchModel()->search([])->query->andWhere(['groupby' => 'place', 'limit' => ALL]);
        $res = [];
        foreach ($query->all() as $model) {
            $res[$model->place] = $model->place . '   - ' . Yii::t('hipanel', '{0, plural, one{# item} other{# items}}', $model->count);
        }

        return $res;
    }

    public function getMoveTypes()
    {
        return $this->getRefs('type,move', 'hipanel:stock', ['orderby' => 'no_asc', 'with_recursive' => true]);
    }

    public function getSuppliers()
    {
        return $this->getRefs('destination,supplier', 'hipanel:stock', ['orderby' => 'name_asc']);
    }

    public function getRemotehands()
    {
        return $this->getRefs('destination,remotehands', 'hipanel:stock', ['orderby' => 'name_asc']);
    }

    public function getCompanies()
    {
        $companies = Yii::$app->get('cache')->getOrSet([__METHOD__], function () {
            $result = ArrayHelper::map(Ref::find()->where(['gtype' => 'type,part_company', 'select' => 'full'])->all(), 'id', function ($model) {
                return Yii::t('hipanel:stock', $model->label);
            });

            return $result;
        }); // 3600

        return $companies;
    }

    /**
     * @return null|integer
     */
    public function getTrashId()
    {
        $resultId = null;
        $ids = Ref::find()->where(['gtype' => 'destination,trash', 'select' => 'full'])->all();
        foreach ($ids as $model) {
            if ($model->name === 'main')
                $resultId = $model->id;
        }

        return $resultId;
    }
}
