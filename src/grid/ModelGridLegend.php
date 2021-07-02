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
                'color' => '#8B5CF6'
            ],
            'reserved' => [
                'label' => ['hipanel:stock', 'Reserved'],
                'color' => '#10B981',
            ],
            'unused' => [
                'label' => ['hipanel:stock', 'Unused'],
                'color' => '#F59E0B',
            ],
            'rma' => [
                'label' => ['hipanel:stock', 'RMA'],
                'color' => '#EF4444',
            ],
        ];
    }
}
