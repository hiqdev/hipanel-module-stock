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

use hipanel\tests\_support\Page\Authenticated;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Grid;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Page\Widget\Input\Select2;
use hipanel\tests\_support\Page\Widget\Input\Textarea;

class OrderPage extends Authenticated
{
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
            'Parts'
        ]);
    }

    public function ensureICanSeePartsTable(): void
    {
        $I = $this->tester;
        (new Grid($I))->containsColumns([
            'Type',
            'Manufacturer',
            'Part No.',
            'Serial',
            'Last move',
            'Type / Date',
            'Move description',
        ]);
    }

    public function setupOrderForm(array $values): void
    {
        $I = $this->tester;
        $this->fillOrderForm($values);
        $I->pressButton('Save');
        $I->waitForPageUpdate();
    }

    private function fillOrderForm(array $values): void
    {
        $I = $this->tester;

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

    public function seeOrderWasCreated(): string
    {
        $I = $this->tester;
        $I->closeNotification('Order has been created');
        $I->seeInCurrentUrl('/stock/order/view?id=');

        return $I->grabFromCurrentUrl('~id=(\d+)~');
    }
}
