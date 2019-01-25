<?php

namespace hipanel\modules\stock\tests\_support\Page\part;

use hipanel\tests\_support\Page\Authenticated;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;

class SellModalWindow extends Authenticated
{
    /**
     * @param $sellData
     * @throws \Exception
     */
    public function fillSellWindowFields($sellData): void
    {
        $I = $this->tester;

        $base = 'div.modal-body[data-action-url$=sell] ';

        (new Select2($I, $base . 'select[id$=client_id]'))
            ->setValue($sellData['client_id']);

        $I->click($base . 'div[class$=date]>span:last-child');
        $I->click('td.day.active');
        $I->click('span.hour.active');
        $I->click('span.minute.active');

        (new Input($I, $base . 'textarea[id$=description]'))
            ->setValue($sellData['descr']);
        $this->fillPartsPriceFields($sellData['prices']);
    }

    /**
     * @param $partsQty
     */
    protected function fillPartsPriceFields($partsQty): void
    {
        $this->tester->executeJS(<<<JS
            var partQty = arguments[0];
            var selector = 'div[class$=sell] div.row input[type=text][id^=partsell]';
            document.querySelectorAll(selector).forEach(function(part, i) {
                part.value = partQty[i];
                $(part).trigger('mouseup');
            });
JS
            , [$partsQty]);
    }

    public function seePartsWereSold(): void
    {
        $this->tester->closeNotification('Parts have been successfully sold.');
    }

}
