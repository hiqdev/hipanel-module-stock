<?php

namespace hipanel\modules\stock\models;

use hipanel\base\ModelTrait;

class ProfitReport extends \hipanel\base\Model
{
    use ModelTrait;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [
                [
                    'obj_id',
                    'currency',
                    'date',
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
