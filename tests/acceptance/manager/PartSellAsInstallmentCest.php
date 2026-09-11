<?php

namespace hipanel\modules\stock\tests\acceptance\manager;

use hipanel\helpers\Url;
use hipanel\tests\_support\Step\Acceptance\Manager;

class PartSellAsInstallmentCest
{
    public function _before(Manager $I): void
    {
        $I->login();
    }

    public function ensureSellAsInstallmentModalRendersFormFields(Manager $I): void
    {
        $I->amOnPage(Url::to(['@part/sell-as-installment', 'selection' => ['348490210']]));
        $I->seeElement('#part-sell-as-installment-form');
        $I->seeElement('[name$="[client_id]"]');
        $I->seeElement('[name="PartSellAsInstallmentForm[currency]"]');
        $I->seeElement('[name="PartSellAsInstallmentForm[monthly_sum]"]');
        $I->seeElement('[name="PartSellAsInstallmentForm[since]"]');
        $I->seeElement('[name="PartSellAsInstallmentForm[months]"]');
    }

    public function ensureAllSelectedPartsAreListedForOneSharedSubmission(Manager $I): void
    {
        $I->amOnPage(Url::to(['@part/sell-as-installment', 'selection' => ['318396623', '318396624']]));
        $I->seeInSource('value="318396623"');
        $I->seeInSource('value="318396624"');
    }
}
