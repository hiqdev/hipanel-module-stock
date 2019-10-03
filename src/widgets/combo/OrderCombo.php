<?php

namespace hipanel\modules\stock\widgets\combo;

use hiqdev\combo\Combo;

class OrderCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'stock/order';

    /** {@inheritdoc} */
    public $name = 'name';

    /** {@inheritdoc} */
    public $url = '/stock/order/search';

    /** {@inheritdoc} */
    public $_return = ['id', 'seller', 'no'];

    /** {@inheritdoc} */
    public $_primaryFilter = 'name_ilike';
}
