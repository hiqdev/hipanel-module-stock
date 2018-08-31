<?php

namespace hipanel\modules\stock\tests\acceptance\manager;

use hipanel\helpers\Url;
use hipanel\modules\stock\tests\_support\Page\part\Index;
use hipanel\tests\_support\Step\Acceptance\Manager;

class PartsSellingCest
{
    public function ensurePartsPageWorks(Manager $I): void
    {
        $I->login();
        $I->needPage(Url::to('@part'));
    }

    public function ensureICanSellParts(Manager $I): void
    {
        $page = new Index($I);

        $page->filterBySerial('MG_TEST_PART');
        $page->selectPart(1);
        $page->selectPart(2);
        $page->selectPart(3);

        $page->openSellWindow();

        $sellData = $this->getSellData();
        $page->fillSellWindowFields($sellData);
        $I->wait(10);
    }

    protected function getSellData(): array
    {
        return [
            'client_id' => 'hipanel_test_user@hiqdev.com',
            'currency'  => 'eur'
        ];
    }
}
