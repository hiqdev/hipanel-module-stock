<?php

namespace hipanel\modules\stock\widgets\combo;

use hiqdev\combo\Combo;
use yii\helpers\ArrayHelper;

class PartnoCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'stock/partno';

    /** {@inheritdoc} */
    public $name = 'partno';

    /** {@inheritdoc} */
    public $url = '/stock/part/index';

    /** {@inheritdoc} */
    public $_return = ['id'];

    /** {@inheritdoc} */
    public $_rename = ['text' => 'partno'];
}