<?php

declare(strict_types=1);


namespace hipanel\modules\stock\widgets\combo;

use hipanel\helpers\ArrayHelper;

class WithDeletedSourceCombo extends SourceCombo
{
    public function getFilter()
    {
        return ArrayHelper::merge(parent::getFilter(), [
            'show_deleted' => ['format' => 1],
            'limit' => ['format' => '50'],
        ]);
    }
}
