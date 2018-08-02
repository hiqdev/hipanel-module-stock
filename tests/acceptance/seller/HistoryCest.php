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
        $this->ensureICanSeeAdvancedSearchBox();
        $this->ensureICanSeeLegendBox();
        $this->ensureICanSeeBulkSearchBox();
    }

    protected function ensureICanSeeAdvancedSearchBox()
    {
        $this->index->containsFilters([
            new Select2('Part No.'),
            new Select2('Type'),
            new Select2('Source'),
            new Select2('Destination'),
            new Input('Serial'),
            new Input('Move description'),
            new Input('Order No.'),
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
