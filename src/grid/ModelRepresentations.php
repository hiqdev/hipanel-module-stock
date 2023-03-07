<?php

namespace hipanel\modules\stock\grid;

use hipanel\modules\stock\helpers\StockLocationsProvider;
use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

class ModelRepresentations extends RepresentationCollection
{
    public function __construct(private readonly StockLocationsProvider $provider)
    {
        parent::__construct();
    }

    protected function fillRepresentations()
    {
        $this->representations = array_filter([
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => [
                    'checkbox',
                    'type',
                    'brand',
                    'model',
                    'descr',
                    'partno',

                    ...array_values($this->provider->getLocations()),

                    'last_prices',
                    'model_group',
                ],
            ],
        ]);
    }
}
