<?php

declare(strict_types=1);

namespace hipanel\modules\stock\grid;

use hipanel\grid\RefColumn;
use Yii;

/**
 * Class CompanyColumn
 * @package hipanel\modules\stock\grid
 */
class CompanyColumn extends RefColumn
{
    public $filterOptions = ['class' => 'narrow-filter'];
    public $format = 'raw';
    public $gtype = 'type,part_company';
    public $findOptions = [
        'select' => 'id_label',
        'mapOptions' => [
            'from' => 'id',
        ],
    ];

    public function init(): void
    {
        $this->label = Yii::t('hipanel:stock', 'Company');
        $this->value = static fn($model): ?string => $model->company;
    }
}
