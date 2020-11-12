<?php

namespace hipanel\modules\stock\tests\acceptance\manager;

use hipanel\helpers\Url;
use hipanel\modules\stock\tests\_support\Page\part\SellModalWindow;
use hipanel\tests\_support\Page\IndexPage;
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

    /**
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureICanSellParts(Manager $I): void
    {
        $partIndex      = new IndexPage($I);
        $I->needPage(Url::to('@part'));

        $this->sellData = $this->getSellData();
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
