<?php

namespace hipanel\modules\stock\models;

use hipanel\base\ModelTrait;

/**
 * Class PartWithProfit
 * @package hipanel\modules\stock\models
 *
 * @property string $comment
 * @property string $total
 * @property string $uu
 * @property string $stock
 * @property string $rma
 * @property string $rent
 * @property string $leasing
 * @property string $buyout
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
                    'rent',
                    'leasing',
                    'buyout',
                ],
                'safe',
            ]
        ]);
    }

}
