<?php

namespace hipanel\modules\stock\tests\acceptance\manager\part;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Page\Widget\Input\Textarea;
use hipanel\tests\_support\Step\Acceptance\Manager;

class PartIndexCest
{
    private IndexPage $index;

    public function _before(Manager $I): void
    {
        $this->index = new IndexPage($I);
    }

    public function ensurePartsPageWorks(Manager $I): void
    {
        $I->login();
        $I->needPage(Url::to('@part'));
    }

    public function ensureIndexPageWorks(Manager $I): void
    {
        $I->login();
        $I->needPage(Url::to('@part'));
        $I->see('Parts', 'h1');
        $I->seeLink('Create', Url::to('create'));
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeLegendBox();
        $this->ensureICanSeeBulkSearchBox();
    }

    private function ensureICanSeeAdvancedSearchBox(Manager $I): void
    {
        $this->index->containsFilters([
            Select2::asAdvancedSearch($I, 'Part No.'),
            Select2::asAdvancedSearch($I, 'Types'),
            Select2::asAdvancedSearch($I, 'Status'),
            Select2::asAdvancedSearch($I, 'Manufacturers'),
            Input::asAdvancedSearch($I, 'Serial'),
            Select2::asAdvancedSearch($I, 'Parts'),
            Input::asAdvancedSearch($I, 'Move description'),
            Textarea::asAdvancedSearch($I, 'Source'),
            Textarea::asAdvancedSearch($I, 'Destination'),
            Select2::asAdvancedSearch($I, 'Location'),
            Select2::asAdvancedSearch($I, 'Currency'),
            Input::asAdvancedSearch($I, 'Limit'),
            Input::asAdvancedSearch($I, 'Reserve'),
            Select2::asAdvancedSearch($I, 'Buyers'),
            Input::asAdvancedSearch($I, 'First move'),
            Input::asAdvancedSearch($I, 'Last move'),
        ]);
    }

    private function ensureICanSeeLegendBox(): void
    {
        $this->index->containsLegend([
            'Inuse',
            'Reserve',
            'Stock',
            'RMA',
            'TRASH',
        ]);
    }

    private function ensureICanSeeBulkSearchBox(): void
    {
        $this->index->containsBulkButtons([
            'RMA',
            'Move',
            'Bulk actions',
            'Trash',
        ]);
        $this->index->containsColumns([
            'Type',
            'Manufacturer',
            'Part No.',
            'Serial',
            'Last move',
            'Type / Date',
            'Move description',
        ], 'common');
        $this->index->containsColumns([
            'Type',
            'Manufacturer',
            'Part No.',
            'Serial',
            'Created',
            'Purchase price',
            'Place',
        ], 'report');
    }
}
