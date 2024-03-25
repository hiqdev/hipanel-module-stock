<?php

/*
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\stock\widgets\combo;

use hiqdev\combo\Combo;

class PartnoCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'stock/partno';

    /** {@inheritdoc} */
    public $name = 'partno';

    /** {@inheritdoc} */
    public $url = '/stock/model/index';

    /** {@inheritdoc} */
    public $_return = ['id', 'warranty_months'];

    /** {@inheritdoc} */
    public $_rename = ['text' => 'partno'];
}
