<?php

namespace hipanel\modules\stock\tests\acceptance\manager\part;

use Codeception\Example;
use DateTime;
use hipanel\helpers\Url;
use hipanel\modules\stock\tests\_support\Page\part\SellModalWindow;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Step\Acceptance\Manager;

class PartSellCest
{
    private IndexPage $index;

    public function _before(Manager $I): void
    {
        $this->index = new IndexPage($I);
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

    protected function getSellData(): array
    {
        return [
            [
                'contact_id' => 'Test Manager',
                'currency' => 'eur',
                'descr' => 'test description ' . uniqid(),
                'type' => 'HW purchase',
                'prices' => [250, 300, 442],
                'time' => (new DateTime())->modify('-1 day')->format('Y-m-d H:i'),
            ],
        ];
    }
}
