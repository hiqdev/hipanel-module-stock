<?php

namespace hipanel\modules\stock\tests\acceptance\manager;

use hipanel\helpers\Url;
use hipanel\modules\stock\tests\_support\Page\model\Create;
use hipanel\tests\_support\Step\Acceptance\Manager;

class ModelsCest
{
    public function ensureModelsPageWorks(Manager $I): void
    {
        $I->login();
        $I->needPage(Url::to('@model'));
    }

    /**
     * Checks work of model form buttons (add, copy, remove).
     *
     * @param Manager $I
     * @throws \Exception
     */
    public function ensureModelManageButtonsWorks(Manager $I): void
    {
        $page = new Create($I);
        $I->needPage(Url::to('@model/create'));

        $n = 0;

        $I->seeNumberOfElements('div.item', ++$n);

        $page->addModel();
        $I->seeNumberOfElements('div.item', ++$n);

        $page->addModel();
        $I->seeNumberOfElements('div.item', ++$n);

        $page->copyModel();
        $I->seeNumberOfElements('div.item', ++$n);

        $page->removeModel();
        $I->seeNumberOfElements('div.item', --$n);

        $page->removeModel();
        $I->seeNumberOfElements('div.item', --$n);

        $page->removeModel();
        $I->seeNumberOfElements('div.item', --$n);
    }

    /**
     * Tries to create a new single model without any data.
     *
     * Expects error due blank fields.
     *
     * @param Manager $I
     * @throws \Exception
     */
    public function ensureICantCreateModelWithoutData(Manager $I): void
    {
        $page = new Create($I);

        $I->needPage(Url::to('@model/create'));
        $I->pressButton('Save');

        $page->containsBlankFieldsError(['Type', 'Model', 'Part No.']);
    }

    /**
     * Tries to create two models at time.
     *
     * Expects successful models creation.
     *
     * @param Manager $I
     * @throws \Exception
     */
    public function ensureICanCreateSeveralModel(Manager $I):void
    {
        $page = new Create($I);

        $I->needPage(Url::to('@model/create'));
        $modelData = $this->getModelData('SSD', 'Kingston', '1-2TB OLD SSD');
        $page->fillModelFields($modelData);

        $modelData = $this->getModelData('CPU', 'AMD', 'X11_1xCPU');
        $page->addModel($modelData);

        $I->pressButton('Save');
        $page->seeModelsWereCreated();
    }

    /**
     * Creates one model.
     *
     * Expects successful creation.
     *
     * @param Manager $I
     * @throws \Exception
     */
    public function ensureICanCreateModel(Manager $I): void
    {
        $page = new Create($I);

        $I->needPage(Url::to('@model/create'));
        $modelData = $this->getModelData('RAM', 'Kingston', '32GB DDR3');
        $page->fillModelFields($modelData);

        $I->pressButton('Save');
        $page->seeModelWasCreated();
    }

    protected function getModelData($type, $brand, $groupId): array
    {
        $uid = uniqid();

        return [
            'type'      => $type,
            'brand'     => $brand,
            'group_id'  => $groupId,
            'model'     => 'MG_TEST_MODEL' . $uid,
            'partno'    => 'MG_TEST_PARTNO' . $uid,
            'url'       => 'test_url',
            'short'     => 'Short description',
            'descr'     => 'Extended description'
        ];
    }
}
