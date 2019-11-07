<?php

namespace hipanel\modules\stock\widgets\combo;

use hipanel\widgets\RefCombo;

/**
 * Class CompanyCombo
 * @package hipanel\modules\stock\widgets\combo
 */
class CompanyCombo extends RefCombo
{
    /**
     * @inheritdoc
     */
    public $multiple = false;

    /**
     * @inheritdoc
     */
    public $gtype = 'type,part_company';

    /**
     * @inheritdoc
     */
    public $findOptions = [
        'select' => 'id_label',
        'mapOptions' => [
            'from' => 'id',
        ],
    ];
}

