<?php


namespace hipanel\modules\stock\widgets;


use Yii;
use yii\base\Widget;

/**
 * Class SummaryWidget
 * @package hipanel\modules\stock\widgets
 */
class SummaryWidget extends Widget
{
    /**
     * @var array
     */
    public $total_sums;

    /**
     * @var array
     */
    public $local_sums;

    /**
     * @inheritDoc
     */
    public function run()
    {
        $locals = $this->getSumsString($this->local_sums);
        $totals = $this->getSumsString($this->total_sums);
        return '<div class="summary">' .
            ($totals ? Yii::t('hipanel:stock', 'TOTAL') . ':' . $totals : null) .
            ($locals ? '<br><span class="text-muted">' . Yii::t('hipanel', 'on screen') . ':' . $locals . '</span>' : null) .
            '</div>';
    }

    /**
     * @param array $sumsArray
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    private function getSumsString(array $sumsArray): string
    {
        $totals = '';
        foreach ($sumsArray as $cur => $sum) {
            if ($cur && $sum > 0) {
                $totals .= ' &nbsp; <b>' . Yii::$app->formatter->asCurrency($sum, $cur) . '</b>';
            }
        }
        return $totals;
    }
}
