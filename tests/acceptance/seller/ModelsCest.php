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
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeLegendBox();
        $this->ensureICanSeeBulkSearchBox();
    }

    private function ensureICanSeeAdvancedSearchBox(Seller $I)
    {
        $this->index->containsFilters([
            Select2::asAdvancedSearch($I, 'Type'),
            Select2::asAdvancedSearch($I, 'Brand'),
            Select2::asAdvancedSearch($I, 'Status'),
            Input::asAdvancedSearch($I, 'Filter'),
            Input::asAdvancedSearch($I, 'Model'),
            Input::asAdvancedSearch($I, 'Description'),
            Input::asAdvancedSearch($I, 'Part No.'),
            Input::asAdvancedSearch($I, 'Group'),
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
