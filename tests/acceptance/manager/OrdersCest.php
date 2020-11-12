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
use hipanel\tests\_support\Step\Acceptance\Manager;
use hipanel\modules\stock\tests\_support\Page\order\OrderPage;

class OrdersCest
{
    private string $order_id;

    private OrderPage $page;

    private array $values;

    public function _before(Manager $I): void
    {
        $this->page = new OrderPage($I);
        $this->values = $this->getOrderValues();
    }

    public function ensureIndexPageWorks(Manager $I): void
    {
        $I->needPage(Url::to('@order'));
        $I->see('Orders', 'h1');
        $this->page->ensureICanSeeAdvancedSearchBox();
        $this->page->ensureICanSeeColumns();
    }

    public function ensureICantCreateEmptyOrder(Manager $I): void
    {
        $I->needPage(Url::to('@order/create'));
        $I->click('Save');
        $I->waitForPageUpdate();
        $I->waitForText('# cannot be blank.');
        $I->waitForText('Time cannot be blank.');
        $I->waitForText('Seller cannot be blank.');
        $I->waitForText('Buyer cannot be blank.');
    }

    public function ensureICanCreateOrder(Manager $I): void
    {
        $I->needPage(Url::to('@order/create'));
        $this->page->setupOrderForm($this->values);
        $this->order_id = $this->page->seeOrderWasCreated();
    }

    /**
     * @depends ensureICanCreateOrder
     */
    public function ensureICantCreateOrderWithSameNoResellerCombo(Manager $I): void
    {
        $I->needPage(Url::to('@order/create'));
        $this->page->setupOrderForm($this->values);
        $I->waitForText('The combination No. and Reseller has already been taken.');
    }

    /**
     * @depends ensureICanCreateOrder
     */
    public function ensureICanSeeViewPage(Manager $I): void
    {
        $I->needPage(Url::to('@order/view?id=' . $this->order_id));
        $I->see($this->values['name'], 'h1');

        $this->page->ensureICanSeePartsTable();
    }

    /**
     * @depends ensureICanCreateOrder
     */
    public function ensureICanUpdateOrder(Manager $I): void
    {
        $page = $this->page;
        $I->needPage(Url::to('@order/update?id='.$this->order_id));
        $this->updateValues();
        $page->setupOrderForm($this->values);
        $I->closeNotification('Order has been updated');
    }

    /**
     * @depends ensureICanCreateOrder
     */
    public function ensureICanDeleteOrder(Manager $I): void
    {
        $I->needPage(Url::to('@order/view?id='.$this->order_id));
        $I->click('Delete');
        $I->acceptPopup();
        $I->waitForPageUpdate();
        $I->closeNotification('Order has been deleted');
    }

    /**
     * @depends ensureICanDeleteOrder
     */
    public function ensureICanFullDeleteOrder(Manager $I): void
    {
        $this->ensureICanDeleteOrder($I);
    }

    protected function getOrderValues(): array
    {
        return [
            'type'      => 'hardware',
            'seller_id' => 'Test Manager',
            'buyer_id'  => 'Test Admin',
            'state'     => 'OK',
            'no'        => 'testNO1448',
            'time'      => '2019-04-03 01:30',
            'name'      => 'simple name',
        ];
    }

    protected function updateValues(): void
    {
        $this->values['no'] .= '1';
        $this->values['name'] = '';
        $this->values['state'] = 'New';
    }
}
