<?php

namespace hipanel\modules\stock\widgets\combo;

use hiqdev\combo\Combo;

class ModelCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'stock/modelId';

    /** {@inheritdoc} */
    public $name = 'partno';

    /** {@inheritdoc} */
    public $url = '/stock/model/index';

    /** {@inheritdoc} */
    public $_return = ['id'];

    /** {@inheritdoc} */
    public $_rename = ['text' => 'partno'];
}
