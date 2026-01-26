<?php

declare(strict_types=1);

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
use hipanel\modules\stock\Module;
use hipanel\modules\stock\repositories\StockRepository;
use Yii;
use yii\base\Event;

/**
 * Class ModelGroupController
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class ModelGroupController extends CrudController
{
    public function __construct(
        $id,
        Module $module,
        private readonly StockRepository $stockRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
    }
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    'create' => 'model.create',
                    'update' => 'model.update',
                    'delete' => 'model.delete',
                    'copy' => 'model.create',
                    '*' => 'model.update',
                ],
            ],
        ]);
    }

    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
            'index' => [
                'class' => IndexAction::class,
                'data' => [
                    'stockRepository' => $this->stockRepository,
                ],
                'filterStorageMap' => [
                    'alias_in' => 'stock.model-group.alias_in',
                ],
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
                'on beforePerform' => function (Event $event) {
                    $event->sender->getDataProvider()->query->joinWith(['models'])->andWhere(['with_models' => 1]);
                },
            ],
        ]);
    }
}
