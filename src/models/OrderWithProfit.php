<?php

namespace hipanel\modules\stock\models;

use hipanel\base\ModelTrait;

/**
 * Class OrderWithProfit
 * @package hipanel\modules\stock\models
 *
 * @property string $currency
 * @property string $total
 * @property string $uu
 * @property string $stock
 * @property string $rma
 * @property string $rent
 * @property string $leasing
 * @property string $buyout
 */
class OrderWithProfit extends Order
{
    use ModelTrait;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [
                [
                    'currency',
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
