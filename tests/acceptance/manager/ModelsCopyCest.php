<?php

namespace hipanel\modules\stock\tests\acceptance\manager;

use hipanel\helpers\Url;
use hipanel\modules\stock\tests\_support\Page\model\Create;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Step\Acceptance\Manager;

class ModelsCopyCest
{
    /**
     * Create new model, and check copy functionality
     *
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureCopyModelWork(Manager $I): void
    {
        $modelIndex = new IndexPage($I);
        $page = new Create($I);

        $I->needPage(Url::to('@model/create'));
        $modelData = $this->getModelData('Chassis', 'noname', '32GB DDR3');
        $page->fillModelFields($modelData);
        $I->pressButton('Save');
        $page->seeModelWasCreated();

        $I->needPage(Url::to('@model'));
        $input = new Input($I, "//thead/tr/td/input[contains(@name,'ModelSearch[model_like]')]");
        $modelIndex->filterBy($input, $modelData['model']);
        $modelIndex->selectTableRowByNumber(1);
        $I->pressButton('Copy');
        $I->waitForPageUpdate();

        (new Input($I, "//input[@value='".$modelData['partno']."']"))
            ->setValue('COPY_TEST_'.$modelData['model']);
        $I->pressButton('Save');
        $I->waitForPageUpdate();

        $I->needPage(Url::to('@model'));
        $modelIndex->filterBy($input, $modelData['model']);
        foreach (range(1, 2) as $i) {
            $I->see('noname', "//tbody/tr[$i]");
        }
    }

    /**
     * @param string $type
     * @param string $brand
     * @param string $groupId
     * @return array
     */
    protected function getModelData(string $type, string $brand, string $groupId): array
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
