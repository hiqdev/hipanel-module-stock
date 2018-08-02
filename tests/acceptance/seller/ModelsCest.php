<?php

namespace hipanel\modules\stock\tests\acceptance\seller;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Step\Acceptance\Seller;

class ModelsCest
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
        $I->needPage(Url::to('@model'));
        $I->see('Models', 'h1');
        $I->seeLink('Create model', Url::to('create'));
        $this->ensureICanSeeAdvancedSearchBox();
        $this->ensureICanSeeLegendBox();
        $this->ensureICanSeeBulkSearchBox();
    }

    private function ensureICanSeeAdvancedSearchBox()
    {
        $this->index->containsFilters([
            new Select2('Type'),
            new Select2('Brand'),
            new Select2('Status'),
            new Input('Filter'),
            new Input('Model'),
            new Input('Description'),
            new Input('Part No.'),
            new Input('Group'),
        ]);
    }

    private function ensureICanSeeLegendBox()
    {
        $this->index->containsLegend([
            'In stock',
            'Reserved',
            'Unused',
            'RMA',
        ]);
    }

    private function ensureICanSeeBulkSearchBox()
    {
        $this->index->containsBulkButtons([
            'Show for users',
            'Hide from users',
            'Update',
            'Copy',
            'Delete',
        ]);
        $this->index->containsColumns([
            'Type',
            'Brand',
            'Model',
            'Part No.',
            'DTG',
            'SDG',
            'M3',
            'TWR',
            'Last price',
            'Group',
        ]);
    }
}
