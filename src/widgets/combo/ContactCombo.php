<?php

namespace hipanel\modules\stock\widgets\combo;

use hipanel\helpers\ArrayHelper;
use hipanel\modules\client\widgets\combo\ContactCombo as BaseContactCombo;

class ContactCombo extends BaseContactCombo
{
    /** {@inheritdoc} */
    public function getFilter()
    {
        return ArrayHelper::merge(parent::getFilter(), [
            'client' => 'client/client',
        ]);
    }
}

