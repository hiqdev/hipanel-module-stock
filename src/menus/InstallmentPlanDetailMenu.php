<?php
/**
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2026, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\stock\menus;

use hipanel\menus\AbstractDetailMenu;
use hipanel\modules\stock\models\InstallmentPlan;
use Yii;

class InstallmentPlanDetailMenu extends AbstractDetailMenu
{
    public InstallmentPlan $model;

    public function items(): array
    {
        return [
            [
                'label' => Yii::t('hipanel:stock', 'Delete'),
                'icon' => 'fa-trash',
                'url' => ['@installment-plan/delete', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('installment-plan.delete') && !$this->model->isDeleted(),
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'pjax' => '0',
                        'confirm' => Yii::t('hipanel:stock', 'Are you sure you want to delete this installment plan?'),
                        'params' => [
                            'InstallmentPlan[id]' => $this->model->id,
                        ],
                    ],
                ],
            ],
            [
                'label' => Yii::t('hipanel:stock', 'Restore'),
                'icon' => 'fa-undo',
                'url' => ['@installment-plan/restore', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('installment-plan.restore') && $this->model->isDeleted(),
                'linkOptions' => [
                    'data' => [
                        'method' => 'post',
                        'pjax' => '0',
                        'params' => [
                            'InstallmentPlan[id]' => $this->model->id,
                        ],
                    ],
                ],
            ],
        ];
    }
}
