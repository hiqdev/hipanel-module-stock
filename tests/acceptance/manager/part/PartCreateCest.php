<?php

namespace hipanel\modules\stock\tests\acceptance\manager\part;

use hipanel\helpers\Url;
use hipanel\modules\stock\tests\_support\Page\part\Create;
use hipanel\tests\_support\Step\Acceptance\Manager;
use DateTime;

class PartCreateCest
{
    private Create $createPage;

    public function _before(Manager $I): void
    {
        $this->createPage = new Create($I);
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
            'Currency',
        ]);
    }

    /**
     * Tries to create a new single part.
     *
     * Expects successful part creation.
     *
     * @param Manager $I
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
     * @return array
     */
    protected function getPartData(): array
    {
        return [
            'partno' => 'CHASSIS EPYC 7402P',
            'src_id' => 'TEST-DS-01',
            'dst_id' => 'TEST-DS-02',
            'serials' => 'MG_TEST_PART' . uniqid(),
            'move_descr' => 'MG TEST MOVE',
            'price' => 200,
            'currency' => 'usd',
            'company_id' => 'Other',
        ];
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
