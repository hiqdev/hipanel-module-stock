<?php

namespace hipanel\modules\stock\widgets;

class OrderType extends \hipanel\widgets\Type
{
    /** {@inheritdoc} */
    public $model         = [];
    public $values        = [];
    public $defaultValues = [
        'none'    => ['hardware'],
    ];
    public $field = 'type';
    public $i18nDictionary = 'hipanel.stock.order';
}
