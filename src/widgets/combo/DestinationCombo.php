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
