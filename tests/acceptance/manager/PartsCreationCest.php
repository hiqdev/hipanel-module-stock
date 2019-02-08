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
        $I->pressButton('Save');

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

        $I->needPage(Url::to('@part/create'));
        $partData = $this->getPartData();
        $page->fillPartFields($partData);
        $I->waitForJS("return $.active == 0;", 60);

        $I->pressButton('Save');
        $page->seePartWasCreated();
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
        $page = new Create($I);

        $I->needPage(Url::to('@part/create'));
        $page->fillPartFields($this->getPartData());
        $page->addPart($this->getPartData());

        $I->pressButton('Save');
        $page->seePartsWereCreated();
    }

    /**
     * Create and delete new parts
     *
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureICanCreateAndTrashPart(Manager $I): void
    {
        $page = new Create($I);

        $I->needPage(Url::to('@part/create'));
        $partData = $this->getPartData();
        $page->fillPartFields($partData);

        $I->pressButton('Save');
        $page->seePartWasCreated();

        $I->click("//a[contains(text(), 'Delete')]");
        $I->acceptPopup();
        $I->closeNotification('Part has been deleted');
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
            'type'          => 'direct',
            'price'         => 200,
            'currency'      => 'usd',
            'company_id'    => 'Other'
        ];
    }
}
