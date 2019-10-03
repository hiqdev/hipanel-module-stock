<?php

namespace hipanel\modules\stock\models;

use hipanel\base\ModelTrait;

/**
 * Class PartWithProfit
 * @package hipanel\modules\stock\models
 *
 * @property string $currency
 * @property string $comment
 * @property string $total
 * @property string $uu
 * @property string $stock
 * @property string $rma
 * @property string $rent_price
 * @property string $rent_charge
 * @property string $leasing_price
 * @property string $leasing_charge
 * @property string $buyout_price
 * @property string $buyout_charge
 */
class PartWithProfit extends Part
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
                    'comment',
                    'total',
                    'uu',
                    'stock',
                    'rma',
                    "rent_price",
                    "rent_charge",
                    "leasing_price",
                    "leasing_charge",
                    "buyout_price",
                    "buyout_charge",
                ],
                'safe',
            ]
        ]);
    }

}
