<?php

namespace hipanel\modules\stock\tests\acceptance\manager\part;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Textarea;
use hipanel\tests\_support\Step\Acceptance\Manager;

class SetRealSerialsCest
{
    private IndexPage $index;

    public function _before(Manager $I): void
    {
        $I->login();
        $this->index = new IndexPage($I);
    }

    public function ensureICanChangeSerialToReal(Manager $I): void
    {
        $I->needPage(Url::to(['@part/index', 'PartSearch' => ['partno' => 'SC815TQ-600WB', 'dst_name_in' => 'TEST-DS-02']]));

        $rowsCount = $this->index->countRowsInTableBody();
        $I->assertSame(1, $rowsCount);

        $this->setSerialAsPartId($I);
        $this->tryToSetTowSerialsForOnePart($I);

        (new Textarea($I, 'textarea[id*=serials]'))->setValue('test_real_serials_1');
        $I->pressButton('Save');
        $I->waitForPageUpdate();
        $I->closeNotification('The serials have been changed');
    }

    private function setSerialAsPartId(Manager $I): void
    {
        $this->index->selectTableRowByNumber(1);
        $I->click('Bulk actions');
        $I->click('Set serial');
        $I->waitForPageUpdate();
        $I->see('Set serial', '.modal-title');
        $id = $I->grabValueFrom('#set-serial-form input[id*=id]');
        (new Input($I, '#set-serial-form div[class*=serial] input'))->setValue($id);
        $I->pressButton('Submit');
        $I->waitForPageUpdate();
        $I->closeNotification('Serial updated');
    }

    private function tryToSetTowSerialsForOnePart($I): void
    {
        $this->index->selectTableRowByNumber(1);
        $I->click('Bulk actions');
        $I->click('Set real serials');
        $I->waitForPageUpdate();
        $I->see('Set real serials', '.modal-title');
        (new Textarea($I, 'textarea[id*=serials]'))->setValue('test_real_serials_1, test_real_serials_1');
        $I->pressButton('Save');
        $I->waitForPageUpdate();
        $I->waitForText('Serial numbers should have been put in the same amount as the selected parts');
    }
}
