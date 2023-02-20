<?php

namespace hipanel\modules\stock\models\query;

use hiqdev\hiart\ActiveQuery;

/**
 * Class OrderQuery
 * @package hipanel\modules\stock\models\query
 */
class OrderQuery extends ActiveQuery
{
    /**
     * @return $this
     */
    public function withParts(): self
    {
        $this->joinWith('parts');
        $this->andWhere(['with_parts' => true]);

        return $this;
    }

    public function withFiles(): self
    {
        $this->joinWith('files');
        $this->andWhere(['with_files' => true]);

        return $this;
    }

    /**
     * @return $this
     */
    public function withProfit(): self
    {
        $this->joinWith('profit');
        $this->andWhere(['with_profit' => true]);

        return $this;
    }

    /**
     * @return $this
     */
    public function withPartsProfit(): self
    {
        $this->joinWith([
            'partsProfit' => function (ActiveQuery $query) {
                $query->addSelect('selling');
                $query->joinWith('profit');
                $query->andWhere(['with_profit' => true]);
            },
        ]);
        $this->andWhere(['with_parts_profit' => true]);

        return $this;
    }
}
