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
        $this->representations = array_filter([
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => [
                    'checkbox',
                    'client',
                    'serialno', 'model', 'device',
                    'state',
                    'since', 'till', 'quantity',
                    'expected_monthly_sum', 'charged_sum', 'left_sum', 'expected_sum',
                    'actions',
                ],
            ],
        ]);
    }
}
