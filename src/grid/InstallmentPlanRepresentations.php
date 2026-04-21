<?php
/**
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2026, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\stock\grid;

use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

class InstallmentPlanRepresentations extends RepresentationCollection
{
    protected function fillRepresentations()
    {
        $user = Yii::$app->user;
        $this->representations = array_filter([
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => array_filter([
                    'checkbox',
                    'client',
                    'serialno', 'model', 'device',
                    'state',
                    'since', 'till', 'quantity',
                    'expected_monthly_sum', 'charged_sum', 'left_sum', 'expected_sum',
                    $user->can('order.update') ? 'order_name' : null,
                    $user->can('order.update') ? 'company_id' : null,
                    'warranty_till',
                    'actions',
                ]),
            ],
        ]);
    }
}
