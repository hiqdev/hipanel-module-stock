<?php

namespace hipanel\modules\stock\widgets\combo;

use hiqdev\combo\Combo;

class DestinationCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'stock/dst_name';

    /** {@inheritdoc} */
    public $name = 'dst_name';

    /** {@inheritdoc} */
    public $url = '/stock/move/index';

    /** {@inheritdoc} */
    public $_return = ['id'];

    /** {@inheritdoc} */
    public $_rename = ['text' => 'dst_name'];
}