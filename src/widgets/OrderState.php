<?php

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
