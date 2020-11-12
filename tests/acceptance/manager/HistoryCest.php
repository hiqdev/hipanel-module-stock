<?php

namespace hipanel\modules\stock\tests\acceptance\manager;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Step\Acceptance\Manager;

class HistoryCest
{
    /**
     * @var IndexPage
     */
    private $index;

    public function _before(Manager $I): void
    {
        $this->index = new IndexPage($I);
    }

    /**
     * Check index page
     *
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureIndexPageWorks(Manager $I): void
    {
        $I->needPage(Url::to('@move'));
        $I->see('Moves', 'h1');
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeLegendBox();
        $this->ensureICanSeeBulkSearchBox();
    }

    /**
     * Method for check sorting by time
     *
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureSortByTimeWorks(Manager $I): void
    {
        $I->needPage(Url::to('@move'));
        $this->index->checkSortingBy('Time');
    }

    /**
     * Method for check filtering by client
     *
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     * @throws \Exception
     */
    public function ensureFilterByClientWorks(Manager $I): void
    {
        $name = $I->getUsername();
        $selector = '#movesearch-client_id';

        $I->needPage(Url::to('@move'));
        $select = new Select2($I, $selector);
        $select->setValue($name);
        $this->index->filterBy($select, $name);
        $count = $this->index->countRowsInTableBody();
        for ($i = 1 ; $i <= $count; ++$i) {
            $I->see($name, "//tbody/tr[$i]");
        }
    }

    /**
     * Create advanced search contains data
     *
     * @param Manager $I
     */
    protected function ensureICanSeeAdvancedSearchBox(Manager $I): void
    {
        $this->index->containsFilters([
            Select2::asAdvancedSearch($I, 'Part No.'),
            Select2::asAdvancedSearch($I, 'Type'),
            Select2::asAdvancedSearch($I, 'Source'),
            Select2::asAdvancedSearch($I, 'Destination'),
            Input::asAdvancedSearch($I, 'Serial'),
            Input::asAdvancedSearch($I, 'Move description'),
            Input::asAdvancedSearch($I, 'First move'),
        ]);
    }

    /**
     * Create legend contains data
     *
     */
    protected function ensureICanSeeLegendBox(): void
    {
        $this->index->containsLegend([
            'Inuse',
            'Reserve',
            'Stock',
            'RMA',
            'TRASH',
        ]);
    }

    /**
     * Create columns contains data
     *
     * @throws \Codeception\Exception\ModuleException
     */
    protected function ensureICanSeeBulkSearchBox(): void
    {
        $this->index->containsBulkButtons([
            'Delete',
        ]);
        $this->index->containsColumns([
            'Client',
            'Time',
            'Move',
            'Move description',
            'Parts',
        ]);
    }
}

