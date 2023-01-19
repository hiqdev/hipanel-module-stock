<?php

namespace hipanel\modules\stock\tests\acceptance\manager;

use hipanel\helpers\Url;
use Codeception\Example;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\modules\stock\tests\_support\Page\part\SellModalWindow;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\modules\stock\tests\_support\Page\part\Create;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Page\Widget\Input\VueTreeSelect;
use hipanel\tests\_support\Step\Acceptance\Manager;
use DateTime;

class PartsCest
{
    private IndexPage $index;
    private Create $createPage;

    public function _before(Manager $I): void
    {
        $this->index = new IndexPage($I);
        $this->createPage = new Create($I);
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

    /**
     * Checks work of part form buttons (add, copy, remove).
     *
     * @param Manager $I
     * @throws \Exception
     */
    public function ensurePartManageButtonsWorks(Manager $I): void
    {
        $this->createPage = new Create($I);
        $I->needPage(Url::to('@part/create'));

        $n = 0;

        $I->seeNumberOfElements('div.item', ++$n);

        $this->createPage->addPart();
        $I->seeNumberOfElements('div.item', ++$n);

        $this->createPage->addPart();
        $I->seeNumberOfElements('div.item', ++$n);

        $this->createPage->copyPart();
        $I->seeNumberOfElements('div.item', ++$n);

        $this->createPage->removePart();
        $I->seeNumberOfElements('div.item', --$n);

        $this->createPage->removePart();
        $I->seeNumberOfElements('div.item', --$n);

        $this->createPage->removePart();
        $I->seeNumberOfElements('div.item', --$n);
    }

    /**
     * Tries to create a new single part without any data.
     *
     * Expects error due blank fields.
     *
     * @param Manager $I
     * @throws \Exception
     */
    public function ensureICantCreatePartWithoutData(Manager $I): void
    {
        $I->needPage(Url::to('@part/create'));
        $I->pressButton('Save');

        $this->createPage->containsBlankFieldsError([
            'Part No.',
            'Source',
            'Destination',
            'Serials',
            'Move description',
            'Purchase price',
            'Currency'
        ]);
    }

    /**
     * Tries to create a new single part.
     *
     * Expects successful part creation.
     *
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureICanCreatePart(Manager $I): void
    {
        $I->needPage(Url::to('@part/create'));
        $this->createPage->fillPartFields($this->getPartData());
        $I->wait(3);
        $this->createPage->pressSaveButton();
        $this->createPage->seePartWasCreated();
    }

    /**
     * Tries to create several parts.
     *
     * Expects successful parts creation.
     *
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureICanCreateSeveralParts(Manager $I): void
    {
        $I->needPage(Url::to('@part/create'));
        $this->createPage->fillPartFields($this->getPartData());
        $this->createPage->addPart($this->getPartData());
        $this->createPage->pressSaveButton();
        $this->createPage->seePartWasCreated();
    }

    /**
     * Create and delete new parts
     *
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureICanCreateAndTrashPart(Manager $I): void
    {
        $I->needPage(Url::to('@part/create'));
        $this->createPage->fillPartFields($this->getPartData());
        $this->createPage->pressSaveButton();
        $this->createPage->seePartWasCreated();

        $I->click("//a[contains(text(), 'Delete')]");
        $I->acceptPopup();
        $I->closeNotification('Part has been deleted');
    }

    /**
     * @dataProvider getSellData
     */
    public function ensureICanSellParts(Manager $I, Example $example): void
    {
        $I->needPage(Url::to('@part'));
        $sellData = iterator_to_array($example->getIterator());

        $this->index->filterBy(Input::asTableFilter($I, 'Serial'), 'MG_TEST_PART');
        for ($i = 0, $iMax = count($sellData['prices']); $i < $iMax; $i++) {
            $this->index->selectTableRowByNumber($i + 1);
        }
        $I->click("//button[contains(text(), 'Sell parts')]");
        $I->click("//a[text()='Sell parts']");
        $I->waitForPageUpdate();

        $sellModal = new SellModalWindow($I);
        $sellModal->fillSellWindowFields($sellData);
        $I->click('Sell');
        $sellModal->seePartsWereSold();

        $this->ensureSellingBillWasCreated($I, $sellData);
    }

    private function ensureSellingBillWasCreated(Manager $I, array $sellData): void
    {
        $I->needPage(Url::to('@bill'));

        $this->index->filterBy(Input::asTableFilter($I, 'Description'), $sellData['descr']);

        $this->index->openRowMenuByNumber(1);
        $this->index->chooseRowMenuOption('View');

        $I->seeNumberOfElements('tr table  tr[data-key]', count($sellData['prices']));
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
            Select2::asAdvancedSearch($I, 'Source'),
            Select2::asAdvancedSearch($I, 'Destination'),
            Select2::asAdvancedSearch($I, 'Location'),
            Select2::asAdvancedSearch($I, 'Currency'),
            Input::asAdvancedSearch($I, 'Limit'),
            Input::asAdvancedSearch($I, 'Reserve'),
            Select2::asAdvancedSearch($I, 'Buyers'),
            Select2::asAdvancedSearch($I, 'First move'),
            Input::asAdvancedSearch($I, 'Order'),
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
            'First move',
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

    /**
     * @return array
     */
    protected function getPartData(): array
    {
        return [
            'partno'        => 'CHASSIS EPYC 7402P',
            'src_id'        => 'TEST-DS-01',
            'dst_id'        => 'TEST-DS-02',
            'serials'       => 'MG_TEST_PART' . uniqid(),
            'move_descr'    => 'MG TEST MOVE',
            'price'         => 200,
            'currency'      => 'usd',
            'company_id'    => 'Other'
        ];
    }

    protected function getSellData(): array
    {
        return [
            [
                'contact_id'=> 'Test Manager',
                'currency'  => 'eur',
                'descr'     => 'test description ' . uniqid(),
                'type'      => 'HW purchase',
                'prices'    => [250, 300, 442],
                'time'      => (new DateTime())->modify('-1 day')->format('Y-m-d H:i'),
            ]
        ];
    }
}
