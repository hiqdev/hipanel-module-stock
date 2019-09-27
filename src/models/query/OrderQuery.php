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

class OrderQuery extends ActiveQuery
{
    public function withParts(): self
    {
        $this->joinWith('parts');
        $this->andWhere(['with_parts' => true]);

        return $this;
    }

    public function withProfit(): self
    {
        $this->joinWith('profit');
        $this->andWhere(['withProfit' => true]);

        return $this;
    }
}
