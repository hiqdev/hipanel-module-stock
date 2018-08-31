<?php

namespace hipanel\modules\stock\tests\_support\Page\part;

use hipanel\tests\_support\AcceptanceTester;
use hipanel\tests\_support\Page\Authenticated;
use hipanel\tests\_support\Page\Widget\Select2;
use WebDriverKeys;

class Index extends Authenticated
{
    protected $select2;

    public function __construct(AcceptanceTester $I)
    {
        parent::__construct($I);

        $this->select2 = new Select2($I);
    }

    public function filterBySerial(string $serial): void
    {
        $I = $this->tester;

        $selector = 'td input[name*=serial]';
        $I->fillField($selector,  $serial);
        $I->pressKey($selector,WebDriverKeys::ENTER);
        $I->waitForText($serial, 5,  'tr:first-child td:nth-child(5) a');
    }

    public function selectPart(int $n): void
    {
        $I = $this->tester;

        $selector = "tbody tr:nth-child($n) input[type=checkbox]";
        $I->click($selector);
    }

    public function openSellWindow(): void
    {
        $I = $this->tester;

        $I->click('button[data-target$=parts-sell]');
        $I->waitForElement('div.modal-body[data-action-url$=sell] ' .
            'select[id$=client_id]', 5);
    }

    public function fillSellWindowFields($sellData): void
    {
        $I = $this->tester;

        $base = 'div.modal-body[data-action-url$=sell] ';

        $this->select2->open($base . 'select[id$=client_id]');
        $this->select2->fillSearchField($sellData['client_id']);
        $this->select2->chooseOption($sellData['client_id']);

//        $I->selectOption($base . 'select[id$=currency]', $sellData['currency']);

    }
}
