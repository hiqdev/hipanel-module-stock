<?php

namespace hipanel\modules\stock\tests\acceptance\manager;

use hipanel\helpers\Url;
use hipanel\modules\stock\tests\_support\Page\part\Create;
use hipanel\tests\_support\Step\Acceptance\Manager;

class PartsCreationCest
{
    public function ensurePartsPageWorks(Manager $I): void
    {
        $I->login();
        $I->needPage(Url::to('@part'));
    }

    /**
     * Checks work of part form buttons (add, copy, remove).
     *
     * @param Manager $I
     * @throws \Exception
     */
    public function ensurePartManageButtonsWorks(Manager $I): void
    {
        $page = new Create($I);
        $I->needPage(Url::to('@part/create'));

        $n = 0;

        $I->seeNumberOfElements('div.item', ++$n);

        $page->addPart();
        $I->seeNumberOfElements('div.item', ++$n);

        $page->addPart();
        $I->seeNumberOfElements('div.item', ++$n);

        $page->copyPart();
        $I->seeNumberOfElements('div.item', ++$n);

        $page->removePart();
        $I->seeNumberOfElements('div.item', --$n);

        $page->removePart();
        $I->seeNumberOfElements('div.item', --$n);

        $page->removePart();
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
        $page = new Create($I);

        $I->needPage(Url::to('@part/create'));
        $page->save();

        $page->containsBlankFieldsError([
            'Part No.',
            'Source',
            'Destination',
            'Serials',
            'Move description',
            'Purchase price',
            'Currency'
        ]);
    }

    public function ensureICanCreatePart(Manager $I): void
    {
        $page = new Create($I);

        $I->needPage(Url::to('@part/create'));
        $partData = $this->getPartData();
        $page->fillPartFields($partData);

        $page->save();
        $page->seePartWasCreated();
    }

    public function ensureICanCreateSeveralParts(Manager $I): void
    {
        $page = new Create($I);

        $I->needPage(Url::to('@part/create'));
        $page->fillPartFields($this->getPartData());
        $page->addPart($this->getPartData());

        $page->save();
        $page->seePartsWereCreated();
    }

    protected function getPartData(): array
    {
        return [
            'partno'        => 'MG_TEST_PARTNO',
            'src_id'        => 'TEST01',
            'dst_id'        => 'vCDN-soltest',
            'serials'       => 'MG_TEST_PART' . uniqid(),
            'move_descr'    => 'MG_TEST_MOVE',
            'type'          => 'direct',
            'price'         => 200,
            'currency'      => 'usd',
            'company_id'    => 'Other'
        ];
    }
}
