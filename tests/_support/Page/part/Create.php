<?php

namespace hipanel\modules\stock\tests\_support\Page\part;

use hipanel\tests\_support\AcceptanceTester;
use hipanel\tests\_support\Page\Authenticated;
use hipanel\tests\_support\Page\Widget\Select2;

class Create extends Authenticated
{
    protected $select2;

    public function __construct(AcceptanceTester $I)
    {
        parent::__construct($I);

        $this->select2 = new Select2($I);
    }

    /**
     * Adds new part form and fill it with provided data.
     *
     * If data was not provided leaves added form empty.
     *
     * @param array $modelData
     */
    public function addPart($modelData = []): void
    {
        $this->addItem();
        if (empty($modelData)) {
            return;
        }
        $this->fillPartFields($modelData);
    }

    public function fillPartFields($partData): void
    {
        $I = $this->tester;

        $base = 'div.item:last-child ';

        $this->select2->open($base . 'select[id$=partno]');
        $this->select2->fillSearchField($partData['partno']);
        $this->select2->chooseOptionLike($partData['partno']);

        $this->select2->open($base . 'select[id$=src_id]');
        $this->select2->fillSearchField($partData['src_id']);
        $this->select2->chooseOptionLike($partData['src_id']);

        $this->select2->open($base . 'select[id$=dst_id]');
        $this->select2->fillSearchField($partData['dst_id']);
        $this->select2->chooseOptionLike($partData['dst_id']);

        $I->fillField($base . 'input[id$=serials]', $partData['serials']);
        $I->fillField($base . 'input[id$=move_descr]', $partData['move_descr']);

        $I->fillField($base . 'input[id$=price]', $partData['price']);
        $I->click('div.item:last-child  span.caret');
        $I->click("div.item:last-child li a[data-value=$partData[currency]]");

        $I->selectOption($base . 'select[id$=type]', $partData['type']);
        $I->selectOption($base . 'select[id$=company_id]',
                                        $partData['company_id']);
    }

    /**
     * Saves created part(s).
     */
    public function save(): void
    {
        $this->tester->click('button[type=submit]');
    }

    /**
     * Checks whether a part was created successfully and returns its id.
     *
     * @return string - id of created model.
     */
    public function seePartWasCreated(): string
    {
        $I = $this->tester;

        $I->closeNotification('Part has been created');
        $I->seeInCurrentUrl('/stock/part/view?id=');

        return $I->grabFromCurrentUrl('~id=(\d+)~');
    }

    /**
     * Checks whether a parts were created successfully.
     */
    public function seePartsWereCreated(): void
    {
        $I = $this->tester;

        $I->closeNotification('Part has been created');
        $I->seeInCurrentUrl('/stock/part/index');
    }

    /**
     * Adds new part form to the page.
     */
    protected function addItem(): void
    {
        $selector = 'div.item:last-child button[class*=\'add-item btn\']';
        $this->clickElement($selector);
    }

    /**
     * Copies specified part form.
     *
     * The count begins at 1.
     * If method will be called without parameter or with -1 - the last
     * form will be copied.
     *
     * @param int $n - number of part form.
     */
    public function copyPart(int $n = -1): void
    {
        if ($n === -1) {
            $selector = "div.item:last-child button[class*='copy']";
        } else {
            $selector = "div.item:nth-child($n) button[class*='copy']";
        }
        $this->clickElement($selector);
    }

    /**
     * Removes specified part form.
     *
     * The count begins at 1.
     * If method will be called without parameter or with -1 - the last
     * form will be removed.
     *
     * @param int $n - number of part form.
     */
    public function removePart(int $n = -1): void
    {
        if ($n === -1) {
            $selector = "div.item:last-child button[class*='remove-item']";
        } else {
            $selector = "div.item:nth-child($n) button[class*='remove-item']";
        }
        $this->clickElement($selector);
    }

    /**
     * Clicks on specified element.
     *
     * @param $selector - selector of the element that should be clicked.
     */
    protected function clickElement($selector): void
    {
        $this->tester->executeJS(<<<JS
           document.querySelector(arguments[0]).click();
JS
            , [$selector]);
    }

    /**
     * Looking for blank fields errors of the given fields.
     *
     * @param array $fieldsList
     * @throws \Exception
     */
    public function containsBlankFieldsError(array $fieldsList): void
    {
        foreach ($fieldsList as $field) {
            $this->tester->waitForText("$field cannot be blank.");
        }
    }
}
