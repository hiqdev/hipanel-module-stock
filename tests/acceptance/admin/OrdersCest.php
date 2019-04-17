<?php
/**
 * hipanel.advancedhosters.com
 *
 * @link      http://hipanel.advancedhosters.com/
 * @package   hipanel.advancedhosters.com
 * @license   proprietary
 * @copyright Copyright (c) 2016-2019, AdvancedHosters (https://advancedhosters.com/)
 */

namespace hipanel\modules\stock\tests\acceptance\admin;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Step\Acceptance\Admin;
use hipanel\modules\stock\tests\_support\Page\order\Order;

class OrdersCest
{
    /**
     * @var IndexPage
     */
    private $order_id;
    private $model;
    private $index;

    public function _before(Admin $I): void
    {
        $this->model = new Order($I);
        $this->index = new IndexPage($I);
    }

    public function ensureIndexPageWorks(Admin $I): void
    {
        $I->login();
        $I->needPage(Url::to('@order'));
        $I->see('Orders', 'h1');
        $this->model->ensureICanSeeAdvancedSearchBox();
        $this->model->ensureICanSeeBulkSearchBox();
    }

    public function ensureICanCreateOrder(Admin $I): void
    {
        $I->login();
        $I->needPage(Url::to('@order/create'));
        $I->click('Save');
        $I->waitForPageUpdate();
        $I->waitForText('No. cannot be blank.');
        $I->waitForText('Lead time cannot be blank.');
        $this->model->fillOrderForm();
        $I->pressButton('Save');
        $I->waitForPageUpdate();
        $this->order_id = $this->model->seeOrderWasCreated();
    }

    public function ensureICanDeleteOrder(Admin $I): void
    {
        $I->login();
        $I->needPage(Url::to('@order/view?id='.$this->order_id));
        $I->click('Delete');
        $I->acceptPopup();
        $I->waitForPageUpdate();
        $I->waitForText('Order has been deleted');
    }

    public function ensureICanUpdateOrderWithoutChanges(Admin $I): void
    {
        $I->login();
        $I->needPage(Url::to('@order/update?id='.$this->order_id));
        $I->pressButton('Save');
        $I->waitForPageUpdate();
        $I->waitForText('Order has been updated');
    }

    public function ensureICanUpdateOrder(Admin $I): void
    {
        $model = $this->model;
        $I->login();
        $I->needPage(Url::to('@order/update?id='.$this->order_id));
        $model->updateValues();
        $model->fillOrderForm();
        $I->pressButton('Save');
        $I->waitForPageUpdate();
        $I->waitForText('Order has been updated');
    }

    public function ensureIFullDeleteOrder(Admin $I): void
    {
        $this->ensureICanDeleteOrder($I);
    }
}
