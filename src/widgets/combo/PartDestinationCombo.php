<?php

namespace hipanel\modules\stock\widgets\combo;

use hipanel\helpers\ArrayHelper;

class PartDestinationCombo extends DestinationCombo
{
    /** {@inheritdoc} */
    public function getFilter()
    {
        return ArrayHelper::merge(parent::getFilter(), [
            'types' => [
                'format' => [
                    'unused',
                    'old',
                    'setup',
                    'delivery',
                    'reserved',
                    'dedicated',
                    'unmanaged',
                    'jbod',
                    'virtual',
                    'system',
                    'remote',
                    'vdsmaste',
                    'avdsnode',
                    'cloudservers',
                    'cdn',
                    'cdnv2',
                    'cdnpix',
                    'cdnstat',
                    'cloudstorage',
                    'transit',
                    'office',
                    'stock',
                ],
            ],
        ]);
    }
}

