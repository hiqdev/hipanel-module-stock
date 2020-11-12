<?php

namespace hipanel\modules\stock\tests\_support\Page\part;

use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;

class Update extends Create
{
    public function fillPartFields($partData): void
    {
        $I = $this->tester;

        $base = 'div.item:last-child ';

        (new Select2($I, $base . 'select[id$=partno]'))
            ->setValueLike($partData['partno']);

        (new Select2($I, $base . 'select[id$=src_id]'))
            ->setValue($partData['src_id']);

        (new Select2($I, $base . 'select[id$=dst_ids]'))
            ->setValue($partData['dst_id']);

        (new Input($I, $base . 'input[id$=serials]'))
            ->setValue($partData['serials']);

        (new Input($I, $base . 'input[id$=move_descr]'))
            ->setValue($partData['move_descr']);

        (new Input($I, $base . 'input[id$=price]'))
            ->setValue($partData['price']);
        $I->executeJS(";document.querySelector('div.item:last-child li a[data-value=$partData[currency]]').click();");

        (new Dropdown($I, $base . 'select[id$=company_id]'))
            ->setValue($partData['company_id']);
    }

    /**
     * Checks whether a part was created successfully and returns its id.
     */
    public function seePartWasUpdated(string $priceNew): void
    {
        $this->tester->closeNotification('Part has been updated');
        $this->tester->see('$'.$priceNew, '//tbody//tr/td/span');
    }
}
