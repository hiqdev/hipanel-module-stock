<?php

namespace hipanel\modules\stock\grid;

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
                'color' => '#000000',
                'prefix' => '',
            ],
            'reserved' => [
                'label' => ['hipanel:stock', 'Reserved'],
                'color' => '#9400D6',
                'prefix' => '+',
            ],
            'unused' => [
                'label' => ['hipanel:stock', 'Unused'],
                'color' => '#008000',
                'prefix' => '+',
            ],
            'rma' => [
                'label' => ['hipanel:stock', 'RMA'],
                'color' => '#FF0000',
                'prefix' => '/',
            ],
            'chwbox' => [
                'label' => ['hipanel:stock', 'Customer CH Boxes'],
                'color' => '#00c0ef',
                'prefix' => '/',
            ],
            'other' => [
                'label' => ['hipanel:stock', 'Other'],
                'color' => '#999',
                'prefix' => '/',
            ],
        ];
    }
}
