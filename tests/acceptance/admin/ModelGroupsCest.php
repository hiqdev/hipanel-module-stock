<?php

namespace hipanel\modules\stock\tests\acceptance\admin;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Step\Acceptance\Admin;

class ModelGroupsCest
{
    /**
     * @var IndexPage
     */
    private $index;
    private $stocksList;

    public function _before(Admin $I)
    {
        $this->index = new IndexPage($I);
        $this->stocksList = \Yii::$app->params['module.stock.stocks_list'];
    }

    public function ensureIndexPageWorks(Admin $I)
    {
        $I->login();
        $I->needPage(Url::to('@model-group'));
        $I->see('Model groups', 'h1');
        $I->seeLink('Create group', Url::to('create'));
        $this->ensureICanSeeAdvancedSearchBox($I);
        $this->ensureICanSeeBulkSearchBox();
    }

    private function ensureICanSeeAdvancedSearchBox(Admin $I)
    {
        $this->index->containsFilters([
            Input::asAdvancedSearch($I, 'Name'),
        ]);
    }

    private function ensureICanSeeBulkSearchBox()
    {
        $this->index->containsBulkButtons([
            'Update',
            'Copy',
            'Delete',
        ]);
        $this->index->containsColumns([
            'Name',
            ...array_values($this->stocksList),
            'Description',
        ]);
    }
}
