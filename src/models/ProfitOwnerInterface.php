<?php


namespace hipanel\modules\stock\models;


use yii\db\ActiveRecordInterface;

/**
 * Interface ProfitOwnerInterface is a virtual interface which explains model with $profit property
 *
 * @property-read ProfitModelInterface[]|null $profit
 */
interface ProfitOwnerInterface extends ActiveRecordInterface
{
    /**
     * @return ProfitModelInterface[]|null
     */
    public function getProfit(): ?array;
}
