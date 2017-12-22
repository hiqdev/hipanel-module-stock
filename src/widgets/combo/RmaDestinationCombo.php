<?php

namespace hipanel\modules\stock\widgets\combo;

use yii\helpers\ArrayHelper;

class RmaDestinationCombo extends DestinationCombo
{
    public function getFilter()
    {
        return ArrayHelper::merge(parent::getFilter(), [
            'name_like' => ['format' => 'rma'],
        ]);
    }
}
