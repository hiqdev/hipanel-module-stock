<?php

declare(strict_types=1);


namespace hipanel\modules\stock\grid;

use Yii;

enum InStockPartState: string
{
    case stock = 'Stock';
    case chwbox = 'CHW';
    case installed = 'Installed';
    case limit = 'Limit';

    public function label(): string
    {
        return Yii::t('hipanel:stock', $this->value);
    }
}
