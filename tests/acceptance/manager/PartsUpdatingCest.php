<?php

namespace hipanel\modules\stock\tests\acceptance\manager;

use hipanel\helpers\Url;
use hipanel\modules\stock\tests\_support\Page\part\Create;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Step\Acceptance\Manager;

class PartsUpdatingCest
{

    /**
     * Create new part, update price and check result
     *
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureICanCreateAndUpdatePart(Manager $I): void
    {
        $page = new Create($I);

        $I->needPage(Url::to('@part/create'));
        $partData = $this->getPartData();
        $page->fillPartFields($partData);
        $I->pressButton('Save');
        $urlDetails= $page->seePartWasCreated();

        $price = '142.42';
        $I->click("//a[contains(text(), 'Update')]");
        (new Input($I, '//input[@value=42]'))
            ->setValue($price);
        $I->pressButton('Save');
        $I->waitForPageUpdate();
        $I->seeInCurrentUrl('stock/part/view?id='.$urlDetails);
        $I->see('$'.$price, '//tbody//tr/td/span');
    }

    /**
     * @return array
     */
    protected function getPartData(): array
    {
        return [
            'partno'        => 'MG_TEST_PARTNO',
            'src_id'        => 'TEST-DS-01',
            'dst_id'        => 'TEST-DS-02',
            'serials'       => 'MG_TEST_PART' . uniqid(),
            'move_descr'    => 'MG_TEST_MOVE',
            'type'          => 'FROM OLD STOCK',
            'price'         => 42,
            'currency'      => 'usd',
            'company_id'    => 'Other'
        ];
    }
}
