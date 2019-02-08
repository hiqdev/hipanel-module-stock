<?php

namespace hipanel\modules\stock\tests\acceptance\seller;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Step\Acceptance\Seller;

class HistoryCest
{
    /**
     * @var IndexPage
     */
    private $index;

    public function _before(Seller $I)
    {
        $this->index = new IndexPage($I);
    }

    public function ensureIndexPageWorks(Seller $I)
    {
        $I->login();
        $I->needPage(Url::to('@move'));
        $I->see('Moves', 'h1');
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeLegendBox();
        $this->ensureICanSeeBulkSearchBox();
    }

    protected function ensureICanSeeAdvancedSearchBox(Seller $I)
    {
        $this->index->containsFilters([
            Select2::asAdvancedSearch($I, 'Part No.'),
            Select2::asAdvancedSearch($I, 'Type'),
            Select2::asAdvancedSearch($I, 'Source'),
            Select2::asAdvancedSearch($I, 'Destination'),
            Input::asAdvancedSearch($I, 'Serial'),
            Input::asAdvancedSearch($I, 'Move description'),
            Input::asAdvancedSearch($I, 'Order No.'),
        ]);
    }

    protected function ensureICanSeeLegendBox()
    {
        $this->index->containsLegend([
            'Inuse',
            'Reserve',
            'Stock',
            'RMA',
            'TRASH',
        ]);
    }

    protected function ensureICanSeeBulkSearchBox()
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
