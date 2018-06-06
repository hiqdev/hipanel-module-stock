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
            [
                'label' => ['hipanel:stock', 'In stock'],
                'color' => '#333'
            ],
            [
                'label' => ['hipanel:stock', 'Reserved'],
                'color' => '#31708f',
            ],
            [
                'label' => ['hipanel:stock', 'Unused'],
                'color' => '#3c763d',
            ],
            [
                'label' => ['hipanel:stock', 'RMA'],
                'color' => '#a94442',
            ],
        ];
    }
}
