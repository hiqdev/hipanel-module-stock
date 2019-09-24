<?php

namespace hipanel\modules\stock\widgets\combo;

use hiqdev\combo\Combo;

class OrderCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'stock/order';

    /** {@inheritdoc} */
    public $name = 'seller_no';

    /** {@inheritdoc} */
    public $url = '/stock/order/index';

    /** {@inheritdoc} */
    public $_return = ['id', 'seller', 'no'];

    /** {@inheritdoc} */
    public $_primaryFilter = 'seller_no';
}
