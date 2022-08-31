<?php

namespace hipanel\modules\stock\tests\acceptance\manager;

use hipanel\helpers\Url;
use hipanel\modules\stock\tests\_support\Page\model\Create;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Step\Acceptance\Manager;

class ModelsCest
{
    private IndexPage $index;
    private Create $createPage;
    private array $stocksList;

    public function _before(Manager $I): void
    {
        $this->index = new IndexPage($I);
        $this->createPage = new Create($I);
        $this->stocksList = \Yii::$app->params['module.stock.stocks_list'];
    }

    public function ensureModelsPageWorks(Manager $I): void
    {
        $I->login();
        $I->needPage(Url::to('@model'));
    }

    public function ensureIndexPageWorks(Manager $I): void
    {
        $I->login();
        $I->needPage(Url::to('@model'));
        $I->see('Models', 'h1');
        $I->seeLink('Create model', Url::to('create'));
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeLegendBox();
        $this->ensureICanSeeBulkSearchBox();
    }

    /**
     * Checks work of model form buttons (add, copy, remove).
     *
     * @param Manager $I
     * @throws \Exception
     */
    public function ensureModelManageButtonsWorks(Manager $I): void
    {
        $I->needPage(Url::to('@model/create'));

        $n = 0;

        $I->seeNumberOfElements('div.item', ++$n);

        $this->createPage->addModel();
        $I->seeNumberOfElements('div.item', ++$n);

        $this->createPage->addModel();
        $I->seeNumberOfElements('div.item', ++$n);

        $this->createPage->copyModel();
        $I->seeNumberOfElements('div.item', ++$n);

        $this->createPage->removeModel();
        $I->seeNumberOfElements('div.item', --$n);

        $this->createPage->removeModel();
        $I->seeNumberOfElements('div.item', --$n);

        $this->createPage->removeModel();
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
        $I->needPage(Url::to('@model/create'));
        $I->pressButton('Save');

        $this->createPage->containsBlankFieldsError(['Type', 'Model', 'Part No.']);
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
        $I->needPage(Url::to('@model/create'));
        $modelData = $this->getModelData('SSD', 'Kingston', '1-2TB OLD SSD');
        $this->createPage->fillModelFields($modelData);

        $modelData = $this->getModelData('CPU', 'AMD', 'X11_1xCPU');
        $this->createPage->addModel($modelData);

        $I->pressButton('Save');
        $this->createPage->seeModelsWereCreated();
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
        $I->needPage(Url::to('@model/create'));
        $modelData = $this->getModelData('RAM', 'Kingston', '32GB DDR3');
        $this->createPage->fillModelFields($modelData);

        $I->pressButton('Save');
        $this->createPage->seeModelWasCreated();
    }


    /**
     * Method for check filtering by brand
     *
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureFilteredByBrandWork(Manager $I): void
    {
        $I->needPage(Url::to('@model'));
        $filterBy = new Dropdown($I, "tr.filters select[name*=brand]");
        $this->index->checkFilterBy($filterBy, 'AMD');
    }

    /**
     * Method for check sorting
     *
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     */
    public function ensureSortedByTypeWork(Manager $I): void
    {
        $I->needPage(Url::to('@model'));
        $this->index->checkSortingBy('Type');
    }

    /**
     * Create and delete model
     *
     * @param Manager $I
     * @throws \Codeception\Exception\ModuleException
     * @throws \Exception
     */
    public function ensureICanCreateAndDeleteModel(Manager $I): void
    {
        $I->needPage(Url::to('@model/create'));
        $modelData = $this->getModelData('other', 'noname', '32GB DDR3');
        $this->createPage->fillModelFields($modelData);
        $I->pressButton('Save');
        $this->createPage->seeModelWasCreated();

        $I->needPage(Url::to('@model'));
        $this->index->filterBy((Input::asAdvancedSearch($I, 'Model')), $modelData['model']);

        $this->index->selectTableRowByNumber(1);

        $I->pressButton('Delete');
        $I->acceptPopup();
        $I->closeNotification('Model(s) deleted');

        $I->waitForText('No results found.');
    }

    /**
     * @param Manager $I
     * @param $name
     * @throws \Codeception\Exception\ModuleException
     */
    protected function filterModelsByNameAndSelectThem(Manager $I, $name): void
    {
        $selector = "//thead/tr/td/input[contains(@name, 'ModelSearch[model_like]')]";

        $I->needPage(Url::to('@model'));
        $this->index->filterBy(new Input($I, $selector), $name);
        $count = $this->index->countRowsInTableBody();
        foreach (range(1, $count) as $i) {
            $this->index->selectTableRowByNumber($i);
        }
        $I->pressButton('Search');
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

    private function ensureICanSeeAdvancedSearchBox(Manager $I): void
    {
        $this->index->containsFilters([
            Select2::asAdvancedSearch($I, 'Type'),
            Select2::asAdvancedSearch($I, 'Brand'),
            Select2::asAdvancedSearch($I, 'Status'),
            Input::asAdvancedSearch($I, 'Filter'),
            Input::asAdvancedSearch($I, 'Model'),
            Input::asAdvancedSearch($I, 'Description'),
            Input::asAdvancedSearch($I, 'Part No.'),
            Input::asAdvancedSearch($I, 'Group'),
        ]);
    }

    private function ensureICanSeeLegendBox(): void
    {
        $this->index->containsLegend([
            'In stock',
            'Reserved',
            'Unused',
            'RMA',
        ]);
    }

    private function ensureICanSeeBulkSearchBox(): void
    {
        $this->index->containsBulkButtons([
            'Show for users',
            'Hide from users',
            'Update',
            'Copy',
            'Delete',
        ]);
        $this->index->containsColumns([
            'Type',
            'Brand',
            'Model',
            'Description',
            'Part No.',
            ...array_values($this->stocksList),
            'Last price',
            'Group',
        ]);
    }
}
