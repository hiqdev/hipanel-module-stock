<?php

namespace hipanel\modules\stock\tests\acceptance\manager;

use hipanel\helpers\Url;
use hipanel\modules\stock\tests\_support\Page\part\SellModalWindow;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\modules\stock\tests\_support\Page\part\Create;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Step\Acceptance\Manager;

class PartsSellingCest
{
    protected $sellData;

    public function ensurePartsPageWorks(Manager $I): void
    {
        $I->login();
        $I->needPage(Url::to('@part'));
    }

    public function ensureICanCreatePart(Manager $I): void
    {
        $page = new Create($I);
        $this->sellData = $this->getSellData();

        foreach ($this->sellData['prices'] as $part) {
            $I->needPage(Url::to('@part/create'));
            $page->fillPartFields($this->getPartData());
            $page->pressSaveButton();
            $page->seePartWasCreated();
        }
    }

    /**
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureICanSellParts(Manager $I): void
    {
        $partIndex      = new IndexPage($I);
        $I->needPage(Url::to('@part'));

        $partIndex->filterBy(Input::asTableFilter($I, 'Serial'), 'MG_TEST_PART');
        for ($i = 0; $i < count($this->sellData['prices']); $i++) {
            $partIndex->selectTableRowByNumber($i + 1);
        }
        $I->click("//button[contains(text(), 'Sell parts')]");
        $I->click("//a[text()='Sell parts']");
        $I->waitForPageUpdate();

        $sellModal      = new SellModalWindow($I);
        $sellModal->fillSellWindowFields($this->sellData);
        $I->pressButton('Sell');
        $sellModal->seePartsWereSold();
    }

    /**
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureSellingBillWasCreated(Manager $I): void
    {
        $billPage = new IndexPage($I);

        $I->needPage(Url::to('@bill'));

        $this->filterTable($I);
        $billPage->openRowMenuByNumber(1);
        $billPage->chooseRowMenuOption('View');

        $I->seeNumberOfElements('tr table  tr[data-key]', count($this->sellData['prices']));
    }

    /**
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     */
    protected function filterTable(Manager $I): void
    {
        $billPage = new IndexPage($I);

        $billPage->filterBy(Dropdown::asTableFilter($I, 'Type'),
            '-- ' . $this->sellData['type']);

        $billPage->filterBy(Input::asTableFilter($I, 'Description'),
            $this->sellData['descr']);
    }

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
            'contact_id'=> 'Test Manager',
            'currency'  => 'eur',
            'descr'     => 'test description ' . uniqid(),
            'type'      => 'HW purchase',
            'prices'    => [250, 300, 442],
            'time'      => (new \DateTime())->format('Y-m-d H:i'),
        ];
    }
}
