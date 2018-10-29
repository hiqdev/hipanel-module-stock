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

    /**
     * @param Seller $I
     */
    public function ensureIndexPageWorks(Seller $I)
    {
        $I->login();
        $I->needPage(Url::to('@part'));
        $I->see('Parts', 'h1');
        $I->seeLink('Create', Url::to('create'));
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeLegendBox();
        $this->ensureICanSeeBulkSearchBox();
    }

    private function ensureICanSeeAdvancedSearchBox(Seller $I)
    {
        $this->index->containsFilters([
            Select2::asAdvancedSearch($I, 'Part No.'),
            Select2::asAdvancedSearch($I, 'Types'),
            Select2::asAdvancedSearch($I, 'Status'),
            Select2::asAdvancedSearch($I, 'Manufacturers'),
            Input::asAdvancedSearch($I, 'Serial'),
            Select2::asAdvancedSearch($I, 'Parts'),
            Input::asAdvancedSearch($I, 'Move description'),
            Select2::asAdvancedSearch($I, 'Source'),
            Select2::asAdvancedSearch($I, 'Destination'),
            Input::asAdvancedSearch($I, 'Order No.'),
            (Dropdown::asAdvancedSearch($I, 'Company'))->withItems([
                'Company',
                'DataWeb',
                'Other',
            ]),
            Select2::asAdvancedSearch($I, 'Location'),
            Select2::asAdvancedSearch($I, 'Currency'),
            Input::asAdvancedSearch($I, 'Limit'),
            Select2::asAdvancedSearch($I, 'Buyers'),
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

    /**
     * Method for check filtering by brand
     *
     * @param Seller $I
     *
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureFilteredByBrandWork(Seller $I): void
    {
        $partIndex      = new IndexPage($I);
        $I->needPage(Url::to('@part'));
        $partIndex->filterBy(new Dropdown($I, 'tr.filters select[name*=brand]'), 'Kingston');
        $table = $I->grabTextFrom("//tbody");
        $rows = explode("\n", $table);
        $rcount = count($rows);
        for ($i = 1 ; $i < $rcount; ++$i)
        {
            $I->see('Kingston', '//tbody/tr['.$i.']/td[3]');
        }
    }

    public function ensureSortedBySerialWork(Seller $I): void
    {
        $I->needPage(Url::to('@part'));
        $I->click("//button[@id='w0']");
        $I->click("//ul[@class='dropdown-menu']/*/a[contains(text(), 'Serial')]");
        $table = $I->grabTextFrom("//tbody");
        $rows = explode("\n", $table);
        $rcount = count($rows);
        $testSortArray = array();
        for ($i = 1 ; $i < $rcount; ++$i)
        {
            $testSortArray[$i] = $I->grabTextFrom("//tbody/tr[$i]/td[5]");
        }
        $copytestSortArray = $testSortArray;
        sort($copytestSortArray);
        for ($i = 1 ; $i < $rcount; ++$i)
        {
            $I->see($copytestSortArray[$i - 1], '//tbody/tr['.$i.']/td[5]');
        }
    }
}
