<?php
/**
 * Client module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-client
 * @package   hipanel-module-client
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2018, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\stock\widgets;

class OrderState extends \hipanel\widgets\Type
{
    /** {@inheritdoc} */
    public $model         = [];
    public $values        = [];
    public $noneOptions   = [];

    public $defaultValues = [
        'none'    => ['ok', 'new'],
        'danger'  => ['deleted'],
    ];
    public $field = 'state';
    public $i18nDictionary = 'hipanel.stock.order';
}
