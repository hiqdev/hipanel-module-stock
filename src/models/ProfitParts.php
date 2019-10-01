<?php

namespace hipanel\modules\stock\models;

use hipanel\base\ModelTrait;

/**
 * Class ProfitParts
 * @package hipanel\modules\stock\models
 */
class ProfitParts extends Part
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
