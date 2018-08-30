<?php

namespace hipanel\modules\stock\tests\_support\Page\model;

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
     * Adds new model form and fill it with provided data.
     *
     * If data was not provided leaves added form empty.
     *
     * @param array $modelData
     */
    public function addModel($modelData = []): void
    {
        $this->addItem();
        if (empty($modelData)) {
            return;
        }
        $this->fillModelFields($modelData);
    }

    /**
     * Fills last model form on the page with provided data.
     *
     * @param $modelData
     */
    public function fillModelFields($modelData): void
    {
        $I = $this->tester;

        $base = 'div.item:last-child ';

        $this->select2->open($base . 'select[id$=type]');
        $this->select2->fillSearchField($modelData['type']);
        $this->select2->chooseOption($modelData['type']);

        $I->selectOption($base . 'select[id$=brand]', $modelData['brand']);

        $I->fillField($base . 'input[id$=model]', $modelData['model']);
        $I->fillField($base . 'input[id$=partno]', $modelData['partno']);

        $this->select2->open($base . 'select[id*=group_id]');
        $this->select2->fillSearchField($modelData['group_id']);
        $this->select2->chooseOption($modelData['group_id']);

        $I->fillField($base . 'input[id$=url]', $modelData['url']);
        $I->fillField($base . 'textarea[id$=short]', $modelData['short']);
        $I->fillField($base . 'textarea[id$=descr]', $modelData['descr']);
    }

    /**
     * Saves created model(s).
     */
    public function save(): void
    {
        $this->tester->click('button[type=submit]');
    }

    /**
     * Checks whether a model was created successfully and returns its id.
     *
     * @return string - id of created model.
     */
    public function seeModelWasCreated(): string
    {
        $I = $this->tester;

        $I->closeNotification('Model has been created');
        $I->seeInCurrentUrl('/stock/model/view?id=');

        return $I->grabFromCurrentUrl('~id=(\d+)~');
    }

    /**
     * Checks whether a models were created successfully.
     */
    public function seeModelsWereCreated(): void
    {
        $I = $this->tester;

        $I->closeNotification('Model has been created');
        $I->seeInCurrentUrl('/stock/model/index');
    }

    /**
     * Adds new model form to the page.
     */
    protected function addItem(): void
    {
        $selector = 'div.item:last-child button[class*=\'add-item btn\']';
        $this->tester->click($selector);
    }

    /**
     * Copies specified model form.
     *
     * The count begins at 1.
     * If method will be called without parameter or with -1 - the last
     * form will be copied.
     *
     * @param int $n - number of model form.
     */
    public function copyModel(int $n = -1): void
    {
        if ($n == -1) {
            $selector = "div.item:last-child button[class*='copy']";
        } else {
            $selector = "div.item:nth-child($n) button[class*='copy']";
        }
        $this->tester->click($selector);
    }

    /**
     * Removes specified model form.
     *
     * The count begins at 1.
     * If method will be called without parameter or with -1 - the last
     * form will be removed.
     *
     * @param int $n - number of model form.
     */
    public function removeModel(int $n = -1): void
    {
        if ($n == -1) {
            $selector = "div.item:last-child button[class*='remove-item']";
        } else {
            $selector = "div.item:nth-child($n) button[class*='remove-item']";
        }
        $this->tester->click($selector);
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
