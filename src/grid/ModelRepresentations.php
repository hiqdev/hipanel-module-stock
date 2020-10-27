<?php

namespace hipanel\modules\stock\grid;

use hipanel\modules\stock\Module;
use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

class ModelRepresentations extends RepresentationCollection
{
    private Module $module;

    public function __construct(Module $module)
    {
        $this->module = $module;

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

                    ...array_keys($this->module->stocksList),

                    'last_prices',
                    'model_group',
                ],
            ],
        ]);
    }
}
