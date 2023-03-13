<?php

namespace hipanel\modules\stock\tests\acceptance\manager;

use Codeception\Example;
use hipanel\helpers\Url;
use hipanel\modules\stock\tests\_support\Page\part\Create as PartCreate;
use \hipanel\modules\stock\tests\_support\Page\model\Create as ModelCreate;
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
    public function ensureICanCreateAndUpdatePart(Manager $I, Example $data): void
    {
        $partData = $data['partData'];
        $modelData = $data['modelData'];
        $this->createTestModel($I, $modelData);

        $createPage = new PartCreate($I);
        $I->needPage(Url::to('@part/create'));
        $createPage->fillPartFields($partData);
        $createPage->pressSaveButton();
        $createPage->seePartWasCreated();

        $updatePage = new Update($I);
        $I->click("//a[contains(text(), 'Update')]");
        (new Input($I, "//input[@value=$partData[price]]"))
            ->setValue($partData['priceNew']);
        $I->pressButton('Save');
        $updatePage->seePartWasUpdated($partData['priceNew']);
    }

    private function createTestModel(Manager $I, array $modelData): void
    {
        $page = new ModelCreate($I);

        $I->needPage(Url::to('@model/create'));
        $page->fillModelFields($modelData);

        $I->pressButton('Save');
        $page->seeModelWasCreated();
    }

    /**
     * @return array
     */
    protected function getPartData(): iterable
    {
        $uid = uniqid();
        yield [
            'partData' => [
                'partno'        => $partno = 'MG_TEST_PARTNO' . $uid,
                'src_id'        => 'TEST-DS-01',
                'dst_id'        => 'TEST-DS-02',
                'serials'       => 'MG_TEST_PART' . $uid,
                'move_descr'    => 'MG_TEST_MOVE',
                'type'          => 'FROM OLD STOCK',
                'price'         => '42',
                'priceNew'      => '142.42',
                'currency'      => 'usd',
                'company_id'    => 'Other'
            ],
            'modelData' => [
                'type'      => 'SSD',
                'brand'     => 'Kingston',
                'group_id'  => '1-2TB OLD SSD',
                'model'     => 'MG_TEST_MODEL' . $uid,
                'partno'    => $partno,
                'url'       => 'test_url',
                'short'     => 'Short description',
                'descr'     => 'Extended description'
            ],
        ];
    }
}
