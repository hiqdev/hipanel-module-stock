<?php

namespace hipanel\modules\stock\models;

use hipanel\base\ModelTrait;

/**
 * Class ProfitReport
 *
 * @property string $currency
 */
class ProfitReport extends \hipanel\base\Model
{
    use ModelTrait;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [
                [
                    'order_id',
                    'currency',
                    'comment',
                    'total',
                    'uu',
                    'stock',
                    'rma',
                    'rent',
                    'leasing',
                    'buyout',
                ],
                'safe',
            ]
        ]);
    }

}
