<?php

namespace hipanel\modules\stock\grid;

use hipanel\helpers\StringHelper;
use hipanel\widgets\gridLegend\BaseGridLegend;
use hipanel\widgets\gridLegend\GridLegendInterface;

class ModelGridLegend extends BaseGridLegend implements GridLegendInterface
{
    /**
     * @inheritdoc
     */
    public function items()
    {
        return [
            'stock' => [
                'label' => ['hipanel:stock', 'In stock'],
                'color' => '#000000'
            ],
            'reserved' => [
                'label' => ['hipanel:stock', 'Reserved'],
                'color' => '#9400D6',
            ],
            'unused' => [
                'label' => ['hipanel:stock', 'Unused'],
                'color' => '#008000',
            ],
            'rma' => [
                'label' => ['hipanel:stock', 'RMA'],
                'color' => '#FF0000',
            ],
        ];
    }
}
