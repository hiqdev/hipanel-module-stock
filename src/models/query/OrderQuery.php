<?php
/**
 * hipanel.advancedhosters.com
 *
 * @link      http://hipanel.advancedhosters.com/
 * @package   hipanel.advancedhosters.com
 * @license   proprietary
 * @copyright Copyright (c) 2016-2019, AdvancedHosters (https://advancedhosters.com/)
 */

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

    /**
     * @return $this
     */
    public function withOrderProfit(): self
    {
        $this->joinWith('orderWithProfit');
        $this->andWhere(['with_profit' => true]);

        return $this;
    }

    /**
     * @return $this
     */
//    public function withPartProfit(): self
//    {
//        $this->joinWith('partWithProfit');
//        $this->andWhere(['with_profit_parts' => true]);
//
//        return $this;
//    }
}
