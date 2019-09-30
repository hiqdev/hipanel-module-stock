<?php

namespace hipanel\modules\stock\models;

use hipanel\base\ModelTrait;

class ProfitParts extends Part
{
    use ModelTrait;

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
