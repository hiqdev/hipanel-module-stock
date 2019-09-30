<?php

namespace hipanel\modules\stock\grid;

use hipanel\modules\stock\helpers\ProfitRepresentations;
use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

class OrderRepresentations extends RepresentationCollection
{
    public function fillRepresentations()
    {
        $this->representations = array_filter([
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => [
                    'actions', 'type', 'state',
                    'seller', 'buyer', 'parts',
                    'comment', 'time',
                ],
            ],
            'profit-report' => [
                'label' => Yii::t('hipanel', 'profit report'),
                'columns' => ProfitRepresentations::getColumns(function ($attr, $cur) {
                    return [
                        'value' => "{$attr}_{$cur}",
                    ];
                }, ['comment_profit', 'time']),
            ],
        ]);
    }
}
