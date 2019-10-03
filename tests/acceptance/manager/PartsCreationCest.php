<?php

namespace hipanel\modules\stock\tests\acceptance\manager;

use hipanel\helpers\Url;
use hipanel\modules\stock\tests\_support\Page\order\OrderPage;
use hipanel\modules\stock\tests\_support\Page\part\Create;
use hipanel\tests\_support\Step\Acceptance\Manager;

class PartsCreationCest
{

    protected $createPage;
    protected $testOrderData;

    public function _before(Manager $I)
    {
        $this->createPage = new Create($I);
    }

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
        $page = $this->createPage;
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
        $page = $this->createPage;

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
        $page = $this->createPage;
        $this->testOrderData = $this->getTestOrderData();
        $orderPage = new OrderPage($I);
        $I->needPage(Url::to('@order/create'));
        $orderPage->setupOrderForm($this->testOrderData);

        $I->needPage(Url::to('@part/create'));
        $page->fillPartFields($this->getPartData());
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
        $page = $this->createPage;

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
        $page = $this->createPage;

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
            'partno'        => 'E5-2630V3',
            'src_id'        => 'TEST-DS-01',
            'dst_id'        => 'TEST-DS-02',
            'serials'       => 'MG_TEST_PART' . uniqid(),
            'move_descr'    => 'MG TEST MOVE',
            'order_id'      => $this->testOrderData['no'],
            'price'         => 200,
            'currency'      => 'usd',
            'company_id'    => 'Other'
        ];
    }
    /**
     * @return array
     */
    protected function getTestOrderData(): array
    {
        return [
            'type'      => 'hardware',
            'seller_id' => 'Test Admin',
            'buyer_id'  => 'Test User',
            'state'     => 'OK',
            'no'        => 'testNO228' .  (string)time(),
            'time'      => '2019-04-03 01:30',
            'name'      => 'test name',
        ];
    }
}
