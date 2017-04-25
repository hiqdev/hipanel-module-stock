<?php

namespace hipanel\modules\stock\widgets\combo;

use hiqdev\combo\Combo;

class PartCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'stock/part/id';

    /** {@inheritdoc} */
    public $name = 'serial';

    /** {@inheritdoc} */
    public $url = '/stock/part/index';

    /** {@inheritdoc} */
    public $_return = ['id'];

    /** {@inheritdoc} */
    public $_rename = ['text' => 'serial'];
}
