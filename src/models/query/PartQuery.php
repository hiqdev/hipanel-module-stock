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

    public function withProfit(): self
    {
        $this->addSelect('profit')
             ->joinWith('profit')
             ->andWhere(['with_profit' => true]);

        return $this;
    }
}
