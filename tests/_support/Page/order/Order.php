<?php
/**
 * hipanel.advancedhosters.com
 *
 * @link      http://hipanel.advancedhosters.com/
 * @package   hipanel.advancedhosters.com
 * @license   proprietary
 * @copyright Copyright (c) 2016-2019, AdvancedHosters (https://advancedhosters.com/)
 */

namespace hipanel\modules\stock\tests\_support\Page\order;

use hipanel\tests\_support\AcceptanceTester;
use hipanel\tests\_support\Page\Authenticated;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Page\Widget\Input\Textarea;

class Order extends Authenticated
{
    public $values;

    public function __construct(AcceptanceTester $I)
    {
        parent::__construct($I);
        $this->values = $this->getOrderValues();
    }

    public function ensureICanSeeAdvancedSearchBox(): void
    {
        $I = $this->tester;
        (new IndexPage($I))->containsFilters([
            Dropdown::asAdvancedSearch($I, 'Type'),
            Dropdown::asAdvancedSearch($I, 'State'),
            Select2::asAdvancedSearch($I, 'Reseller'),
            Select2::asAdvancedSearch($I, 'Buyer'),
            Input::asAdvancedSearch($I, 'No.'),
            Input::asAdvancedSearch($I, 'Comment'),
        ]);
    }

    public function ensureICanSeeBulkSearchBox(): void
    {
        $I = $this->tester;
        (new IndexPage($I))->containsColumns([
            'Type',
            'State',
            'Reseller',
            'Buyer',
            'No.',
            'Comment',
            'Lead time',
        ]);
    }

    public function fillOrderForm(): void
    {
        $I = $this->tester;
        $values = $this->values;

        (new Dropdown($I, 'select[id*=type]'))
            ->setValue($values['type']);

        (new Dropdown($I, 'select[id*=state]'))
            ->setValue($values['state']);

        (new Select2($I, 'select[id$=seller_id]'))
            ->setValue($values['seller_id']);

        (new Select2($I, 'select[id$=buyer_id]'))
            ->setValue($values['buyer_id']);

        (new Input($I, '#order-no'))
            ->setValue($values['no']);

        (new Input($I, '#order-time'))
            ->setValue($values['time']);

        (new Textarea($I, 'textarea[id$=comment]'))
            ->setValue($values['comment']);
    }

    public function getOrderValues(): array
    {
        return [
            'type' => 'hardware',
            'seller_id' => 'Test Manager',
            'buyer_id' => 'Test Admin',
            'state' => 'OK',
            'no' => 'testNO228',
            'time' => '2019-04-03 01:30',
            'comment' => 'simple comment',
        ];
    }

    public function updateValues(): void
    {
        $this->values['no'] .= '1';
        $this->values['comment'] = '';
    }

    public function seeOrderWasCreated(): string
    {
        $I = $this->tester;
        $I->closeNotification('Order has been created');
        $I->seeInCurrentUrl('/stock/order/view?id=');

        return $I->grabFromCurrentUrl('~id=(\d+)~');
    }
}
