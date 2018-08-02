<?php

namespace hipanel\modules\stock\tests\acceptance\seller;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Step\Acceptance\Seller;

class PartsCest
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
        $I->needPage(Url::to('@part'));
        $I->see('Parts', 'h1');
        $I->seeLink('Create', Url::to('create'));
        $this->ensureICanSeeAdvancedSearchBox();
        $this->ensureICanSeeLegendBox();
        $this->ensureICanSeeBulkSearchBox();
    }

    private function ensureICanSeeAdvancedSearchBox()
    {
        $this->index->containsFilters([
            new Select2('Part No.'),
            new Input('Types'),
            new Select2('Status'),
            new Input('Manufacturers'),
            new Input('Serial'),
            new Input('Parts'),
            new Input('Move description'),
            new Select2('Source'),
            new Select2('Destination'),
            new Input('Order No.'),
            (new Dropdown('partsearch-company_id'))->withItems([
                'Company',
                'DataWeb',
                'Other',
            ]),
            new Input('Location'),
            new Select2('Currency'),
            new Input('Limit'),
            new Input('Buyers'),
        ]);
    }

    private function ensureICanSeeLegendBox()
    {
        $this->index->containsLegend([
            'Inuse',
            'Reserve',
            'Stock',
            'RMA',
            'TRASH',
        ]);
    }

    private function ensureICanSeeBulkSearchBox()
    {
        $this->index->containsBulkButtons([
            'Bulk actions',
            'Set serial',
            'Set price',
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
            'Order No.',
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
        $this->index->containsColumns([
            'Buyer',
            'Last move',
            'Type',
            'Part No.',
            'Serial',
            'Purchase price',
            'Selling time',
        ], 'selling');
    }
}
