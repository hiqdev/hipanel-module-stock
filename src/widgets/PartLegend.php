<?php

namespace hipanel\modules\stock\widgets;

use Yii;
use yii\base\Widget;

class PartLegend extends Widget
{
    public $colClass = 'col-md-3';

    public function run()
    {
        $inuseMessage = Yii::t('hipanel:stock', 'Inuse');
        $stockMessage = Yii::t('hipanel:stock', 'Stock');
        $reserveMessage = Yii::t('hipanel:stock', 'Reserve');
        $rmaMessage = Yii::t('hipanel:stock', 'RMA');
        $trashMessage = Yii::t('hipanel:stock', 'TRASH');

        $html = <<<"HTML"
            <div class="row">
                <div class="{$this->colClass}">
                    <ul class="well well-sm list-unstyled" style="display: flex; justify-content: space-between; margin-bottom: .5em ; padding: .5em 1em">
                        <li>
                            <i class="fa fa-square"
                               style="color: #fff; padding-right: 0.5rem;"></i> {$inuseMessage}
                        </li>
                        <li>
                            <i class="fa fa-square text-green" style="padding-right: 0.5rem;"></i> {$stockMessage} 
                        </li>
                        <li>
                            <i class="fa fa-square text-info" style="padding-right: 0.5rem;"></i> {$reserveMessage}
                        </li>
                        <li>
                            <i class="fa fa-square text-danger" style="padding-right: 0.5rem;"></i> {$rmaMessage} 
                        </li>
                        <li>
                            <i class="fa fa-square text-warning" style="padding-right: 0.5rem;"></i> {$trashMessage}
                        </li>
                    </ul>
                </div>
            </div>
HTML;
        return $html;
    }
}
