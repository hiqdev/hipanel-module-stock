<?php

namespace hipanel\modules\stock\tests\acceptance\manager;

use hipanel\helpers\Url;
use hipanel\modules\stock\tests\_support\Page\part\Create;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Step\Acceptance\Manager;

class PartsUpdatingCest
{
    public function ensurePartsPageWorks(Manager $I): void
    {
        $I->login();
        $I->needPage(Url::to('@part'));
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
        $page = new Create($I);
        $partIndex = new IndexPage($I);
        // create new test part with UPD_ prefix
        $I->needPage(Url::to('@part/create'));
        $partData = $this->getPartData();
        $page->fillPartFields($partData);
        $I->pressButton('Save');
        $page->seePartWasCreated();
        // filtering parts by type UPD_direct
        $partIndex->checkFilterBy('type', 'UPD_direct');
        // need mark part and start check update
    }

    /**
     * @return array
     */
    protected function getPartData(): array
    {
        return [
            'partno'        => 'UPD_MG_TEST_PARTNO',
            'src_id'        => 'UPD_TEST01',
            'dst_id'        => 'UPD_vCDN-soltest',
            'serials'       => 'UPD_MG_TEST_PART' . uniqid(),
            'move_descr'    => 'UPD_MG_TEST_MOVE',
            'type'          => 'UPD_direct',
            'price'         => 200,
            'currency'      => 'usd',
            'company_id'    => 'Other'
        ];
    }
}
