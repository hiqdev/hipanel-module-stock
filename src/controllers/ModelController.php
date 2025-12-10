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

use Closure;
use hipanel\actions\IndexAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartPerformAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\filters\EasyAccessControl;
use hipanel\modules\stock\actions\HardwareSettingsAction;
use hipanel\modules\stock\helpers\StockLocationsProvider;
use hipanel\modules\stock\models\Model;
use hipanel\modules\stock\Module;
use Yii;
use yii\base\Response;
use yii\helpers\Html;

class ModelController extends CrudController
{
    public function __construct(
        $id,
        Module $module,
        private readonly StockLocationsProvider $locationsProvider,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    'create' => 'model.create',
                    'copy' => 'model.create',
                    'update' => 'model.update',
                    'delete' => 'model.delete',
                    'mark-hidden-from-user' => 'model.update',
                    'unmark-hidden-from-user' => 'model.update',
                    '*' => 'model.read',
                ],
            ],
        ]);
    }

    public function actions()
    {
        return array_merge(parent::actions(), [
            'index' => [
                'class' => IndexAction::class, // with_counters
                'findOptions' => ['with_counters' => 1, 'locations' => $this->locationsProvider->getLocations()],
                'data' => fn($action): array => [
                    'types' => $action->controller->getTypes(),
                    'brands' => $action->controller->getBrands(),
                    'states' => $action->controller->getStates(),
                    'exportVariants' => $action->controller->getExportVariants(),
                ],
                'responseVariants' => [
                    'get-total-count' => fn(): int => Model::find()->count(),
                ],
            ],
            'view' => [
                'class' => ViewAction::class,
                'findOptions' => ['with_counters' => 1],
            ],
            'hardware-settings' => [
                'class' => HardwareSettingsAction::class,
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'success' => Yii::t('hipanel:stock', 'Model has been created'),
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getTypes(),
                        'brands' => $action->controller->getBrands(),
                    ];
                },
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:stock', 'Model has been updated'),
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getTypes(),
                        'brands' => $action->controller->getBrands(),
                    ];
                },
            ],
            'copy' => [
                'class' => SmartUpdateAction::class,
                'scenario' => Model::SCENARIO_COPY,
                'success' => Yii::t('hipanel:stock', 'Model has been updated'),
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getTypes(),
                        'brands' => $action->controller->getBrands(),
                    ];
                },
            ],
            'mark-hidden-from-user' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:stock', 'Models have been marked'),
            ],
            'unmark-hidden-from-user' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:stock', 'Models have been marked'),
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
            'delete' => [
                'class' => SmartDeleteAction::class,
                'success' => Yii::t('hipanel:stock', 'Model(s) deleted'),
                'error' => Yii::t(
                    'hipanel:stock',
                    'An error occurred when trying to delete {object}',
                    ['{object}' => Yii::t('hipanel:stock', 'model')]
                ),
            ],
            'restore' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:stock', 'Model(s) restored'),
            ],
        ]);
    }

    public function actionSubform()
    {
        $subFormName = Yii::$app->request->post('subFormName');
        $itemNumber = Yii::$app->request->post('itemNumber');
        if ($subFormName) {
            $validFormNames = $this->getCustomType();
            if (in_array($subFormName, $validFormNames, true)) {
                return $this->renderAjax('_' . $subFormName, ['model' => new Model(), 'i' => $itemNumber]);
            }

            return '';
        }

        return '';
    }

    public function getTypes()
    {
        return $this->getRefs('type,model', 'hipanel:stock');
    }

    public function getStates()
    {
        return $this->getRefs('state,model', 'hipanel');
    }

    public function getDcs()
    {
        return $this->getRefs('type,dc', 'hipanel:stock');
    }

    public function getBrands()
    {
        return $this->getRefs('type,brand', 'hipanel:stock');
    }

    public function getCustomType()
    {
        return ['server', 'chassis', 'motherboard', 'ram', 'hdd', 'cpu'];
    }

    public function actionSetLocations()
    {
        if ($this->request->isPost) {
            $locations = $this->request->post('locations', []);
            $this->locationsProvider->setLocations($locations);
        }
        Yii::$app->end();
    }

    public function actionGetLocations(): Response
    {
        return $this->asJson($this->locationsProvider->getLocations());
    }

    protected function getExportVariants(): Closure
    {
        return function ($exportVariants): array {
            return [
                ...$exportVariants,
                'link' => [
                    'url' => '#',
                    'encode' => false,
                    'label' => Html::tag('i', null, ['class' => 'fa fa-fw fa-link']) . Yii::t('hipanel:stock', 'Link with stock locations'),
                    'linkOptions' => [
                        'class' => 'export-report-link',
                    ],
                ],
            ];
        };
    }
}
