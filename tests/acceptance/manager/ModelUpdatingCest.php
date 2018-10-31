<?php

namespace hipanel\modules\stock\tests\acceptance\manager;

use hipanel\helpers\Url;
use hipanel\modules\stock\tests\_support\Page\model\Create;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Step\Acceptance\Manager;

class ModelUpdatingCest
{
    /**
     * Create new model, update PartNo and check result
     *
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureICanCreateSeveralModel(Manager $I):void
    {
        $page = new Create($I);
        $newValue = 'UPD_TEST';

        $I->login();
        $I->needPage(Url::to('@model/create'));
        $modelData = $this->getModelData('RAM', 'Kingston', '32GB DDR3');
        $page->fillModelFields($modelData);
        $I->pressButton('Save');
        $urlDetails = $page->seeModelWasCreated();
        $I->click("//a[contains(text(), 'Update')]");
        $newValue .= $modelData['uid'];
        (new Input($I, "//input[@value='".$modelData['partno']."']"))
            ->setValue($newValue);
        $I->pressButton('Save');
        $I->waitForPageUpdate();
        $I->seeInCurrentUrl('stock/model/view?id='.$urlDetails);
        $I->see($newValue, '//td/a');
    }

    /**
     * @param $type
     * @param $brand
     * @param $groupId
     * @return array
     */
    protected function getModelData($type, $brand, $groupId): array
    {
        $uid = uniqid();

        return [
            'uid'       => $uid,
            'type'      => $type,
            'brand'     => $brand,
            'group_id'  => $groupId,
            'model'     => 'MG_TEST_MODEL' . $uid,
            'partno'    => 'UP_TEST_' . $uid,
            'url'       => 'test_url',
            'short'     => 'Short description',
            'descr'     => 'Extended description'
        ];
    }
}
