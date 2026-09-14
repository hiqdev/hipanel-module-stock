<?php

namespace hipanel\modules\stock\tests\acceptance\manager;

use hipanel\helpers\Url;
use hipanel\modules\stock\tests\_support\Page\model\Create as ModelCreate;
use hipanel\modules\stock\tests\_support\Page\part\Create as PartCreate;
use hipanel\tests\_support\Step\Acceptance\Manager;

class PartSellAsInstallmentCest
{
    public function _before(Manager $I): void
    {
        $I->login();
    }

    public function ensureSellAsInstallmentModalRendersFormFields(Manager $I): void
    {
        $partId = $this->createFreshPart($I);

        $I->amOnPage(Url::to(['@part/sell-as-installment', 'selection' => [$partId]]));
        $I->seeElement('#part-sell-as-installment-form');
        $I->seeElement('[name$="[client_id]"]');
        $I->seeElement('[name="PartSellAsInstallmentForm[currency]"]');
        $I->seeElement('[name="PartSellAsInstallmentForm[monthly_sum]"]');
        $I->seeElement('[name="PartSellAsInstallmentForm[since]"]');
        $I->seeElement('[name="PartSellAsInstallmentForm[months]"]');
    }

    public function ensureAllSelectedPartsAreListedForOneSharedSubmission(Manager $I): void
    {
        $partIdA = $this->createFreshPart($I);
        $partIdB = $this->createFreshPart($I);

        $I->amOnPage(Url::to(['@part/sell-as-installment', 'selection' => [$partIdA, $partIdB]]));
        $I->seeInSource("value=\"{$partIdA}\"");
        $I->seeInSource("value=\"{$partIdB}\"");
    }

    /**
     * A brand-new part is guaranteed to exist and be selectable regardless of whatever
     * other test/fixture data happens to be in the shared dev DB. Never reuse a fixed
     * fixture part_id here — it's live, mutable data (a part can be rented, deleted, or
     * reassigned between test runs for reasons unrelated to this test).
     */
    private function createFreshPart(Manager $I): int
    {
        $uid = uniqid('', true);

        $modelPage = new ModelCreate($I);
        $I->amOnPage(Url::to('@model/create'));
        $modelPage->fillModelFields([
            'type'     => 'SSD',
            'brand'    => 'Kingston',
            'group_id' => '1-2TB OLD SSD',
            'model'    => 'HQD410_TEST_MODEL' . $uid,
            'partno'   => $partno = 'HQD410_TEST_PARTNO' . $uid,
            'url'      => 'test_url',
            'short'    => 'HQD-410 sell-as-installment test',
            'descr'    => 'HQD-410 sell-as-installment test',
        ]);
        $I->pressButton('Save');
        $modelPage->seeModelWasCreated();

        $partPage = new PartCreate($I);
        $I->amOnPage(Url::to('@part/create'));
        $partPage->fillPartFields([
            'partno'     => $partno,
            'src_id'     => 'TEST-DS-01',
            'dst_id'     => 'TEST-DS-02',
            'serials'    => 'HQD410_TEST_PART' . $uid,
            'move_descr' => 'HQD-410 sell-as-installment test',
            'price'      => '0',
            'currency'   => 'usd',
            'company_id' => 'Other',
        ]);
        $partPage->pressSaveButton();
        $partPage->seePartWasCreated();

        $url = $I->grabFromCurrentUrl();
        preg_match('/id=(\d+)/', (string) $url, $matches);
        $partId = (int) ($matches[1] ?? 0);
        if ($partId === 0) {
            $I->fail('Could not determine created part id from redirect URL: ' . $url);
        }

        return $partId;
    }
}
