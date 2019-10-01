<?php


namespace hipanel\modules\stock\widgets;


use Yii;
use yii\base\Widget;

class SummaryWidget extends Widget
{
    public $total_sums;

    public $local_sums;

    public function run()
    {
        $locals = $this->getSumsString($this->local_sums);
        $totals = $this->getSumsString($this->total_sums);
        return '<div class="summary">' .
            ($totals ? Yii::t('hipanel:stock', 'TOTAL') . ':' . $totals : null) .
            ($locals ? '<br><span class="text-muted">' . Yii::t('hipanel', 'on screen') . ':' . $locals . '</span>' : null) .
            '</div>';
    }

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
