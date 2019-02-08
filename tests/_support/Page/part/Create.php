<?php

namespace hipanel\modules\stock\tests\_support\Page\part;

use Facebook\WebDriver\WebDriverElement;
use hipanel\tests\_support\Page\Authenticated;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;

class Create extends Authenticated
{
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

        (new Dropdown($I, $base . 'select[id$=type]'))
            ->setValue($partData['type']);
        (new Dropdown($I, $base . 'select[id$=company_id]'))
            ->setValue($partData['company_id']);
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
