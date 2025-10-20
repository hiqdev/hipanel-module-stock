<?php

declare(strict_types=1);


namespace hipanel\modules\stock\tests\acceptance\manager;

use Codeception\Exception\ModuleException;
use Exception;
use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Step\Acceptance\Manager;

class ModelGroupsCest
{
    private IndexPage $index;

    public function _before(Manager $I): void
    {
        $this->index = new IndexPage($I);
    }

    public function ensureIndexPageWorks(Manager $I): void
    {
        $I->needPage(Url::to('@model-group'));
        $I->see('Model groups', 'h1');
        $I->seeLink('Create group', Url::to('create'));
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->index->containsBulkButtons([
            'Update',
            'Copy',
            'Delete',
        ]);
        $this->index->containsColumns([
            'Name',
            'Description',
        ]);
        $this->ensureStocksArePresetOnTheIndex($I);
    }

    private function ensureStocksArePresetOnTheIndex(Manager $I): void
    {
        $stockList = $I->grabMultiple('[data-test-stock_alias] a');
        codecept_debug($stockList);
        $I->assertNotEmpty($stockList);
    }

    /**
     * Method for check filtering by name
     *
     * @param Manager $I
     * @throws ModuleException
     * @throws Exception
     */
    public function ensureFilterByNameWorks(Manager $I): void
    {
        $name = '16GB DDR';
        $selector = "//input[contains(@name, 'ModelGroupSearch[name_ilike]')]";

        $I->needPage(Url::to('@model-group'));
        $this->index->filterBy(new Input($I, $selector), $name);
        $count = $this->index->countRowsInTableBody();
        for ($i = 1; $i <= $count; ++$i) {
            $I->see($name, "//tbody/tr[$i]");
        }
    }

    /**
     * Method for check sorting by ID
     *
     * @param Manager $I
     * @throws ModuleException
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

    private function ensureICanSeeAdvancedSearchBox(Manager $I): void
    {
        $this->index->containsFilters([
            Input::asAdvancedSearch($I, 'Name'),
        ]);
    }
}
