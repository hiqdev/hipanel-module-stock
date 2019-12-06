<?php

namespace hipanel\modules\stock\models;

use hipanel\base\ModelTrait;
use hipanel\modules\stock\helpers\ProfitColumns;

/**
 * Class OrderWithProfit
 * @package hipanel\modules\stock\models
 */
class OrderWithProfit extends Order implements ProfitModelInterface
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
                    'total_price',
                    'unused_price',
                    'stock_price',
                    'rma_price',
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

    public function attributeLabels()
    {
        return $this->mergeAttributeLabels(ProfitColumns::getLabels());
    }
}
