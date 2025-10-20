<?php

declare(strict_types=1);


namespace hipanel\modules\stock\repositories;

use hipanel\helpers\ArrayHelper;
use hipanel\modules\stock\models\ModelGroup;

readonly class StockRepository
{
    public function __construct(
        private ModelGroup $modelGroup,
    )
    {
    }

    public function getStockList(): array
    {
        static $list = [];

        if (empty($list)) {
            $aliases = $this->modelGroup::perform('alias-search');
            $list = ArrayHelper::map($aliases, 'alias', 'alias');
        }

        return $list;
    }
}
