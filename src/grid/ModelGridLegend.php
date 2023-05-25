<?php

namespace hipanel\modules\stock\grid;

use hipanel\widgets\gridLegend\BaseGridLegend;
use hipanel\widgets\gridLegend\GridLegendInterface;
use Yii;

class ModelGridLegend extends BaseGridLegend implements GridLegendInterface
{
    /**
     * @inheritdoc
     */
    public function items()
    {
        return [
            'stock' => [
                'label' => Yii::t('hipanel:stock', 'In stock'),
                'color' => '#000000',
                'prefix' => '',
            ],
            'reserved' => [
                'label' => Yii::t('hipanel:stock', 'Reserved'),
                'color' => '#9400D6',
                'prefix' => '+',
            ],
            'unused' => [
                'label' => Yii::t('hipanel:stock', 'Unused'),
                'color' => '#008000',
                'prefix' => '+',
            ],
            'rma' => [
                'label' => Yii::t('hipanel:stock', 'RMA'),
                'color' => '#FF0000',
                'prefix' => '/',
            ],
            'chwbox' => [
                'label' => Yii::t('hipanel:stock', 'Customer CH Boxes'),
                'color' => '#00c0ef',
                'prefix' => '/',
            ],
            'installed' => [
                'label' => Yii::t('hipanel:stock', 'Installed in server'),
                'color' => '#a91f1f',
                'prefix' => '⌗',
            ],
            'other' => [
                'label' => Yii::t('hipanel:stock', 'Other'),
                'color' => '#999',
                'prefix' => '/',
            ],
        ];
    }
}
