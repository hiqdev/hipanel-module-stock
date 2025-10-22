<?php

declare(strict_types=1);


namespace hipanel\modules\stock\widgets;

use hipanel\modules\stock\repositories\StockRepository;
use hiqdev\combo\StaticCombo;
use Yii;

class StockAliasSelect extends StaticCombo
{
    public function init(): void
    {
        $stockRepository = Yii::$container->get(StockRepository::class);
        $storedAliases = $stockRepository->getStoredAliases();
        $allAliases = $stockRepository->getAllAliases();
        $this->multiple = true;
        $this->data = array_combine($allAliases, $allAliases);
        parent::init();
    }
}
