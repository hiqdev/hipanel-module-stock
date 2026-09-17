<?php

namespace hipanel\modules\stock\widgets\combo;

use yii\helpers\ArrayHelper;

class RmaDestinationCombo extends DestinationCombo
{
    public function getFilter()
    {
        return ArrayHelper::merge(parent::getFilter(), [
            'pnames' => ['format' => ['destination,rma', 'destination,trash,rma']],
        ]);
    }
}
