<?php

declare(strict_types=1);

namespace hipanel\modules\stock\grid;

use hipanel\modules\stock\repositories\StockRepository;
use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

class ModelGroupRepresentations extends RepresentationCollection
{
    public function __construct(private readonly StockRepository $stockRepository)
    {
        parent::__construct();
    }

    protected function fillRepresentations(): void
    {
        $this->representations = array_filter([
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => [
                    'checkbox',
                    'name',
                    'descr',
                    ...$this->stockRepository->getStoredAliases(),
                ],
            ],
        ]);
    }
}
