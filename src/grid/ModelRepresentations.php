<?php

namespace hipanel\modules\stock\grid;

use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

class ModelRepresentations extends RepresentationCollection
{
    protected function fillRepresentations()
    {
        $this->representations = array_filter([
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => [
                    'checkbox',
                    'type',
                    'state',
                    'brand',
                    'model',
                    'descr',
                    'partno',
                    'dtg',
                    'sdg',
                    'm3',
                    'twr',
                    'last_prices',
                    'model_group',
                ],
            ],
        ]);
    }
}
