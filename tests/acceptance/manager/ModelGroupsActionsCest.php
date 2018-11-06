<?php

namespace hipanel\modules\stock\tests\acceptance\manager;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Step\Acceptance\Manager;

/**
 * @property string uni
 */
class ModelGroupsActionsCest
{
    /**
     * @var IndexPage
     */
    private $index;

    /**
     * @var string
     */
    private $uniq;

    public function __construct()
    {
        $this->uniq = uniqid();
    }

    public function _before(Manager $I)
    {
        $this->index = new IndexPage($I);
    }

    /**
     * Creating new ModelGroup with filling all inputs, checking results after save
     *
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     * @throws \Exception
     */
    public function ensureCreateModelGroupWorks(Manager $I): void
    {
        $I->needPage(Url::to('@model-group/create'));
        $I->pressButton('Save');
        $I->waitForPageUpdate();
        $I->waitForText('Name cannot be blank.');
        $I->click("//button[contains(@class, 'add-item')]");
        $I->click("//button[contains(@class, 'add-item')]");
        foreach (range(0,2) as $i) {
            (new Input($I, "//input[@name='ModelGroup[$i][name]']"))
                ->setValue("TEST_" . $this->uniq . "_New_" . $i);
            (new Input($I, "//textarea[contains(@name, 'ModelGroup[$i][descr]')]"))
                ->setValue("Test description for $i item");
            (new Input($I, "//input[@name='ModelGroup[$i][limit_dtg]']"))
                ->setValue(($i + 1) * 10);
            (new Input($I, "//input[@name='ModelGroup[$i][limit_sdg]']"))
                ->setValue(($i + 1) * 10);
            (new Input($I, "//input[@name='ModelGroup[$i][limit_m3]']"))
                ->setValue(($i + 1) * 10);
            (new Input($I, "//input[@name='ModelGroup[$i][limit_twr]']"))
                ->setValue(($i + 1) * 10);
        }
        $I->pressButton('Save');
        $I->waitForPageUpdate();
        $I->closeNotification('Created');
        $I->seeInCurrentUrl('/stock/model-group/index');

        $name = 'TEST_' . $this->uniq . '_';
        $selector = "//input[contains(@name, 'ModelGroupSearch[name_ilike]')]";
        $this->index->filterBy(new Input($I, $selector), $name);
        $count = $this->index->countRowsInTableBody();
        foreach (range(1, $count) as $i) {
            $I->see(($i) * 10, "//tbody/tr[$i]");
        }
    }

    /**
     * Creating copies of the ModelGroup created in ensureCreateModelGroupWorks
     *
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureCopyModelGroupWorks(Manager $I): void
    {
        $count = $this->filterModelsByNameAndSelectThem($I, 'New');
        $I->pressButton('Copy');
        $this->fillInputAndPressSaveButton($I, $count, 'Copy');
        $I->closeNotification('Created');
        $I->seeInCurrentUrl('/stock/model-group/index');
    }

    /**
     * Updating ModelGroup created in ensureCopyModelGroupWorks
     *
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureUpdateModelGroupWorks(Manager $I): void
    {
        $count = $this->filterModelsByNameAndSelectThem($I, 'Copy');
        $I->pressButton('Update');
        $this->fillInputAndPressSaveButton($I, $count, 'Updated');
        $I->closeNotification('Updated');
        $I->seeInCurrentUrl('/stock/model-group/index');
    }


    /**
     * Deleting all test ModelGroup
     *
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureDeleteModelGroupWorks(Manager $I): void
    {
        $this->filterModelsByNameAndSelectThem($I, '');
        $I->pressButton('Delete');
        $I->acceptPopup();
        $I->closeNotification('Deleted');
        $I->see('No results found.', '//tbody');
    }

    /**
     * @param Manager $I
     * @param string $lastOperations
     * @return int
     * @throws \Codeception\Exception\ModuleException
     */
    private function filterModelsByNameAndSelectThem(Manager $I, string $lastOperations): int
    {
        $name = 'TEST_' . $this->uniq . '_' . $lastOperations;
        $selector = "//input[contains(@name, 'ModelGroupSearch[name_ilike]')]";

        $I->needPage(Url::to('@model-group'));
        $this->index->filterBy(new Input($I, $selector), $name);
        $count = $this->index->countRowsInTableBody();
        foreach (range(1, $count) as $i) {
            $this->index->selectTableRowByNumber($i);
        }
        return $count;
    }

    /**
     * @param Manager $I
     * @param int $count
     * @param string $action
     * @throws \Codeception\Exception\ModuleException
     */
    private function fillInputAndPressSaveButton(Manager $I, int $count, string $action): void
    {
        foreach (range(0, $count - 1) as $i) {
            (new Input($I, "//input[@name='ModelGroup[$i][name]']"))
                ->setValue("TEST_" . $this->uniq . "_" . $action . "_" . $i);
        }
        $I->pressButton('Save');
        $I->waitForPageUpdate();
    }
}

