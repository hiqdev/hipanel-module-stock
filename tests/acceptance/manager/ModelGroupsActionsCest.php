<?php

namespace hipanel\modules\stock\tests\acceptance\manager;

use Codeception\Attribute\Depends;
use Codeception\Exception\ModuleException;
use hipanel\helpers\Url;
use hipanel\modules\stock\tests\_support\Page\modelGroup\Create;
use hipanel\modules\stock\tests\_support\Page\modelGroup\Index;
use hipanel\modules\stock\tests\_support\Page\modelGroup\Update;
use hipanel\tests\_support\Step\Acceptance\Manager;

class ModelGroupsActionsCest
{
    const int ADDITIONAL_FORMS = 1;

    private Index $index;
    private Create $create;
    private Update $update;
    private string $nameTemplate;
    private array $stocks = [];

    public function __construct()
    {
        $this->nameTemplate = 'TEST_' . uniqid() . '_';
    }

    public function _before(Manager $I)
    {
        $this->index = new Index($I);
        $this->create = new Create($I);
        $this->update = new Update($I);
    }

    public function index(Manager $I): void
    {
        $I->needPage(Url::to('@model-group'));
        $I->see('Model groups', 'h1');
        $I->seeLink('Create group', Url::to('create'));
        $this->stocks = $I->grabMultiple('[data-test-stock_alias] a');
    }

    public function ensureValidationWorks(Manager $I): void
    {
        $this->create->toPage();
        $I->pressButton('Save');
        $I->waitForPageUpdate();
        $I->waitForText('Name cannot be blank.');
    }

    /**
     * Creating a new ModelGroup with filling all inputs, checking results after save
     *
     * @param Manager $I
     * @throws ModuleException
     */
    #[Depends('index')]
    public function ensureCreateModelGroupWorks(Manager $I): void
    {
        $this->create->toPage();
        $this->create->addAdditionalForms(self::ADDITIONAL_FORMS);

        foreach (range(0, self::ADDITIONAL_FORMS) as $i) {
            /** stock list can't be added in provider because of codeception app initialization */
            $this->create->addModelGroupItem([
                'num' => $i,
                'name' => $this->nameTemplate . 'New_' . $i,
                'description' => "Test description for $i item",
                'stockList' => array_combine(
                    array_values($this->stocks),
                    array_map(fn($i) => (string)($i * 10), range(1, count($this->stocks)))
                ),
            ]);
        }
        $this->create->save();

        $this->index->checkfilterByName($this->nameTemplate);
    }

    /**
     * Creating copies of the ModelGroup created in ensureCreateModelGroupWorks
     *
     * @param Manager $I
     * @throws ModuleException
     */
    #[Depends('index')]
    public function ensureCopyModelGroupWorks(Manager $I): void
    {
        $I->needPage(Url::to('@model-group'));
        $count = $this->index->filterModelsByNameAndSelectThem($I, $this->nameTemplate . 'New');
        $I->pressButton('Copy');
        $this->update->fillInputAndPressSaveButton($I, $count, $this->nameTemplate . 'Copy');
        $I->closeNotification('Created');
        $I->seeInCurrentUrl('/stock/model-group/index');
    }

    /**
     * Updating ModelGroup created in ensureCopyModelGroupWorks
     *
     * @param Manager $I
     * @throws ModuleException
     */
    public function ensureUpdateModelGroupWorks(Manager $I): void
    {
        $I->needPage(Url::to('@model-group'));
        $count = $this->index->filterModelsByNameAndSelectThem($I, $this->nameTemplate . 'Copy');
        $I->pressButton('Update');
        $this->update->fillInputAndPressSaveButton($I, $count, $this->nameTemplate . 'Updated');
        $I->closeNotification('Updated');
        $I->seeInCurrentUrl('/stock/model-group/index');
    }

    /**
     * Deleting all test ModelGroup
     *
     * @param Manager $I
     * @throws ModuleException
     */
    public function ensureDeleteModelGroupWorks(Manager $I): void
    {
        $I->needPage(Url::to('@model-group'));
        $this->index->filterModelsByNameAndSelectThem($I, $this->nameTemplate);
        $I->pressButton('Delete');
        $I->acceptPopup();
        $I->closeNotification('Deleted');
        $I->see('No results found.', '//tbody');
    }
}
