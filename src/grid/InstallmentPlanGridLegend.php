<?php
/**
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2026, HiQDev (http://hiqdev.com/)
 */

declare(strict_types=1);

namespace hipanel\modules\stock\grid;

use hipanel\modules\stock\models\InstallmentPlan;
use hipanel\widgets\gridLegend\BaseGridLegend;
use hipanel\widgets\gridLegend\GridLegendInterface;
use yii\helpers\Html;
use Yii;

class InstallmentPlanGridLegend extends BaseGridLegend implements GridLegendInterface
{
    public function items(): array
    {
        return [
            'ongoing' => [
                'label' => Html::tag('span', 'Ongoing', ['class' => 'label label-info']) . ' - ' . Yii::t('hipanel:stock', 'Installment is ongoing'),
                'color' => '#d9edf7',
                'rule' => $this->model->state === InstallmentPlan::STATE_ONGOING,
                'columns' => [
                    'view_link' => ['style' => 'border-left: 5px solid #d9edf7 !important'],
                    'actions' => ['style' => 'border-left: 5px solid #d9edf7 !important'],
                ],
            ],
            'finished' => [
                'label' => Html::tag('span', 'Finished', ['class' => 'label label-success']) . ' - ' . Yii::t('hipanel:stock', 'Installment is finished'),
                'color' => '#dff0d8',
                'rule' => $this->model->state === InstallmentPlan::STATE_FINISHED,
                'columns' => [
                    'view_link' => ['style' => 'border-left: 5px solid #dff0d8 !important'],
                    'actions' => ['style' => 'border-left: 5px solid #dff0d8 !important'],
                ],
            ],
            'buyout' => [
                'label' => Html::tag('span', 'Buyout', ['class' => 'label label-warning']) . ' - ' . Yii::t('hipanel:stock', 'Part is bought out'),
                'color' => '#fcf8e3',
                'rule' => $this->model->state === InstallmentPlan::STATE_BUYOUT,
                'columns' => [
                    'view_link' => ['style' => 'border-left: 5px solid #fcf8e3 !important'],
                    'actions' => ['style' => 'border-left: 5px solid #fcf8e3 !important'],
                ],
            ],
            'interrupted' => [
                'label' => Html::tag('span', 'Interrupted', ['class' => 'label label-danger']) . ' - ' . Yii::t('hipanel:stock', 'Installment is interrupted'),
                'color' => '#f2dede',
                'rule' => $this->model->state === InstallmentPlan::STATE_INTERRUPTED,
                'columns' => [
                    'view_link' => ['style' => 'border-left: 5px solid #f2dede !important'],
                    'actions' => ['style' => 'border-left: 5px solid #f2dede !important'],
                ],
            ],
            'ambiguous' => [
                'label' => Html::tag('span', 'Ambiguous', ['class' => 'label label-danger']) . ' - ' . Yii::t('hipanel:stock', 'Installment state is ambiguous'),
                'color' => '#f2dede',
                'rule' => $this->model->state === InstallmentPlan::STATE_AMBIGUOUS,
                'columns' => [
                    'view_link' => ['style' => 'border-left: 5px solid #f2dede !important'],
                    'actions' => ['style' => 'border-left: 5px solid #f2dede !important'],
                ],
            ],
            'deleted' => [
                'label' => Html::tag('span', 'Deleted', ['class' => 'label label-default']) . ' - ' . Yii::t('hipanel:stock', 'Installment plan is deleted'),
                'color' => '#CCCCCC',
                'rule' => $this->model->state === InstallmentPlan::STATE_DELETED,
                'columns' => [
                    'view_link' => ['style' => 'border-left: 5px solid #CCCCCC !important'],
                    'actions' => ['style' => 'border-left: 5px solid #CCCCCC !important'],
                ],
            ],
        ];
    }
}
