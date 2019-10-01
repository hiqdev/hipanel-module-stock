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
        $locals = '';
        $totals = '';
        if (is_array($this->total_sums)) {
            foreach ($this->total_sums as $cur => $sum) {
                if ($cur && $sum > 0) {
                    $totals .= ' &nbsp; <b>' . Yii::$app->formatter->asCurrency($sum, $cur) . '</b>';
                }
            }
        }
        if (is_array($this->local_sums)) {
            foreach ($this->local_sums as $cur => $sum) {
                if ($cur && $sum > 0) {
                    $locals .= ' &nbsp; <b>' . Yii::$app->formatter->asCurrency($sum, $cur) . '</b>';
                }
            }
        }
        return '<div class="summary">' .
            ($totals ? Yii::t('hipanel:stock', 'TOTAL') . ':' . $totals : null) .
            ($locals ? '<br><span class="text-muted">' . Yii::t('hipanel', 'on screen') . ':' . $locals . '</span>' : null) .
            '</div>';
    }
}
