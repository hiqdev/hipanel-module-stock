<?php
/**
 * hipanel.advancedhosters.com
 *
 * @link      http://hipanel.advancedhosters.com/
 * @package   hipanel.advancedhosters.com
 * @license   proprietary
 * @copyright Copyright (c) 2016-2019, AdvancedHosters (https://advancedhosters.com/)
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
