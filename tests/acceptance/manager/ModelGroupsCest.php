<?php

namespace hipanel\modules\stock\tests\acceptance\manager;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Step\Acceptance\Manager;

class ModelGroupsCest
{
    /**
     * @var IndexPage
     */
    private $index;

    public function _before(Manager $I)
    {
        $this->index = new IndexPage($I);
    }

    public function ensureIndexPageWorks(Manager $I)
    {
        $I->needPage(Url::to('@model-group'));
        $I->see('Model groups', 'h1');
        $I->seeLink('Create group', Url::to('create'));
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeBulkSearchBox();
    }
    /**
     * Method for check filtering by name
     *
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     * @throws \Exception
     */
    public function ensureFilterByNameWorks(Manager $I): void
    {
        $name = '16GB DDR';
        $selector = "//input[contains(@name, 'ModelGroupSearch[name_ilike]')]";

        $I->needPage(Url::to('@model-group'));
        $this->index->filterBy(new Input($I, $selector), $name);
        $count = $this->index->countRowsInTableBody();
        for ($i = 1 ; $i <= $count; ++$i) {
            $I->see($name, "//tbody/tr[$i]");
        }
    }

    /**
     * Method for check sorting by ID
     *
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureSortByIdWorks(Manager $I): void
    {
        $I->needPage(Url::to('@model-group'));
        $I->click("//button[contains(text(), 'Sort')]");
        $I->click("//ul//a[contains(text(), 'ID')]");
        $I->waitForPageUpdate();

        $count = $this->index->countRowsInTableBody();
        $dataKey = [];
        for ($i = 1; $i <= $count; ++$i) {
            $dataKey[$i] = $I->grabAttributeFrom("//tbody/tr[$i]", 'data-key');
        }
        sort($dataKey);
        for ($i = 1; $i <= $count; ++$i) {
            $I->seeElement("//tbody/tr[$i]", ['data-key' => $dataKey[$i - 1]]);
        }
    }
    private function ensureICanSeeAdvancedSearchBox(Manager $I)
    {
        $this->index->containsFilters([
            Input::asAdvancedSearch($I, 'Name'),
        ]);
    }

    private function ensureICanSeeBulkSearchBox()
    {
        $this->index->containsBulkButtons([
            'Update',
            'Copy',
            'Delete',
        ]);
        $this->index->containsColumns([
            'Name',
            'DTG',
            'SDG',
            'M3',
            'TWR',
            'Description',
        ]);
    }
}

