<?php

namespace hipanel\modules\stock\tests\_support\Page\model;

use hipanel\tests\_support\Page\Authenticated;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Page\Widget\Input\Textarea;

class Create extends Authenticated
{
    /**
     * Adds new model form and fill it with provided data.
     *
     * If data was not provided leaves added form empty.
     *
     * @param array $modelData
     * @throws \Exception
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
     * @throws \Exception
     */
    public function fillModelFields($modelData): void
    {
        $I = $this->tester;

        $base = 'div.item:last-child ';

        (new Select2($I, $base . 'select[id$=type]'))
            ->setValue($modelData['type']);

        (new Dropdown($I, $base . 'select[id$=brand]'))
            ->setValue($modelData['brand']);

        (new Input($I, $base . 'input[id$=model]'))
            ->setValue($modelData['model']);

        (new Input($I, $base . 'input[id$=partno]'))
            ->setValue($modelData['partno']);
        $I->waitForPageUpdate();

        (new Select2($I, $base . 'select[id$=group_id]'))
            ->setValue($modelData['group_id']);

        (new Input($I, $base . 'input[id$=url]'))
            ->setValue($modelData['url']);

        (new Textarea($I, $base . 'textarea[id$=short]'))
            ->setValue($modelData['short']);

        (new Textarea($I, $base . 'textarea[id$=descr]'))
            ->setValue($modelData['descr']);
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
        $this->clickElement($selector);
        $this->tester->waitForPageUpdate();
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
        if ($n === -1) {
            $selector = "div.item:last-child button[class*='copy']";
        } else {
            $selector = "div.item:nth-child($n) button[class*='copy']";
        }
        $this->clickElement($selector);
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
