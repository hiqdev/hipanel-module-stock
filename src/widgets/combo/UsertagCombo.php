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
use yii\helpers\ArrayHelper;

class UsertagCombo extends Combo
{
    public $profileType = '';

    /** {@inheritdoc} */
    public $type = 'stock/usertag';

    /** {@inheritdoc} */
    public $name = 'name';

    /** {@inheritdoc} */
    public $url = '/stock/usertag/index';

    /** {@inheritdoc} */
    public $_return = ['id'];
}
