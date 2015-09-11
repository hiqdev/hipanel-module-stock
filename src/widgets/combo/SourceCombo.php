<?php

namespace hipanel\modules\stock\widgets\combo;

use common\components\Lang;
use hiqdev\combo\Combo;

class SourceCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'stock/src_name';

    /** {@inheritdoc} */
    public $name = 'src_name';

    /** {@inheritdoc} */
    public $url = '/stock/move/index';

    /** {@inheritdoc} */
    public $_return = ['id'];

    /** {@inheritdoc} */
    public $_rename = ['text' => 'src_name'];
}