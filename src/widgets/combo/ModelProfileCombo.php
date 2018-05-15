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

class ModelProfileCombo extends ProfileCombo
{
    /** {@inheritdoc} */
    public $type = 'stock/model-group';

    /** {@inheritdoc} */
    public $url = '/stock/model-group/index';
}
