<?php

declare(strict_types=1);

namespace hipanel\modules\stock\models\query;

use hiqdev\hiart\ActiveQuery;

class PartQuery extends ActiveQuery
{
    public function withSale(): self
    {
        $this->joinWith('sale');
        $this->addSelect(['sale']);

        return $this;
    }
}
