<?php

namespace hipanel\modules\stock\tests\acceptance\manager;

use Codeception\Example;
use hipanel\helpers\Url;
use hipanel\modules\stock\tests\_support\Page\part\Create;
use hipanel\modules\stock\tests\_support\Page\part\Update;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Step\Acceptance\Manager;

class PartsUpdatingCest
{

    /**
     * Create new part, update price and check result
     *
     * @dataProvider getPartData
     */
    public function ensureICanCreateAndUpdatePart(Manager $I, Example $partData): void
    {
        $createPage = new Create($I);
        $I->needPage(Url::to('@part/create'));
        $createPage->fillPartFields($partData);
        $createPage->pressSaveButton();
        $createPage->seePartWasCreated();

        $updatePage = new Update($I);
        $I->click("//a[contains(text(), 'Update')]");
        (new Input($I, "//input[@value=$partData[price]]"))
            ->setValue($partData['priceNew']);
        $I->pressButton('Save');
        $I->closeNotification('Part has been updated');
        $I->see('$'.$partData['priceNew'], '//tbody//tr/td/span');
        $updatePage->seePartWasUpdated($partData['priceNew']);
    }

    /**
     * @return array
     */
    protected function getPartData(): iterable
    {
        yield [
            'partno'        => 'MG_TEST_PARTNO',
            'src_id'        => 'TEST-DS-01',
            'dst_id'        => 'TEST-DS-02',
            'serials'       => 'MG_TEST_PART' . uniqid(),
            'move_descr'    => 'MG_TEST_MOVE',
            'type'          => 'FROM OLD STOCK',
            'price'         => 42,
            'priceNew'      => '142.42',
            'currency'      => 'usd',
            'company_id'    => 'Other'
        ];
    }
}
