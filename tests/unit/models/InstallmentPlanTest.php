<?php

declare(strict_types=1);

namespace hipanel\modules\stock\tests\unit\models;

use hipanel\modules\stock\models\InstallmentPlan;
use hipanel\modules\stock\models\InstallmentPlanItem;
use PHPUnit\Framework\TestCase;

final class InstallmentPlanTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        if (!class_exists(\Yii::class, false)) {
            $baseObject = new \ReflectionClass(\yii\base\BaseObject::class);
            require_once dirname($baseObject->getFileName(), 2) . '/Yii.php';
        }
    }

    public function testGetItemsModelsMapsAndSortsItemsFromApiPayload(): void
    {
        $payload = [
            'id' => 399537751,
            'items' => [
                [
                    'id' => 1000189,
                    'installment_plan_id' => 399537751,
                    'month' => '2026-06-01 00:00:00',
                    'no' => 11,
                    'sum' => '2.00',
                    'currency' => 'eur',
                    'charge_id' => null,
                    'charge_sum' => null,
                    'bill_id' => null,
                ],
                [
                    'id' => 1000179,
                    'installment_plan_id' => 399537751,
                    'month' => '2025-08-01 00:00:00',
                    'no' => 1,
                    'sum' => '2.00',
                    'currency' => 'eur',
                    'charge_id' => 722695277,
                    'charge_sum' => '2.00',
                    'bill_id' => 398025309,
                ],
                [
                    'id' => 1000186,
                    'installment_plan_id' => 399537751,
                    'month' => '2026-03-01 00:00:00',
                    'no' => 8,
                    'sum' => '2.00',
                    'currency' => 'eur',
                    'charge_id' => null,
                    'charge_sum' => null,
                    'bill_id' => null,
                ],
            ],
        ];

        $plan = new InstallmentPlan($payload);

        $items = $plan->getItemsModels();

        $this->assertCount(3, $items);
        $this->assertContainsOnlyInstancesOf(InstallmentPlanItem::class, $items);
        $this->assertSame([1, 8, 11], array_map(static fn(InstallmentPlanItem $item): ?int => $item->no, $items));

        $first = $items[0];
        $this->assertSame(1000179, $first->id);
        $this->assertSame(399537751, $first->installment_plan_id);
        $this->assertSame('2025-08-01 00:00:00', $first->month);
        $this->assertSame('eur', $first->currency);
        $this->assertSame(722695277, $first->charge_id);
        $this->assertSame('2.00', (string) $first->charge_sum);
        $this->assertSame(398025309, $first->bill_id);
        $this->assertTrue($first->isPaid());

        $unpaid = $items[1];
        $this->assertNull($unpaid->charge_id);
        $this->assertNull($unpaid->charge_sum);
        $this->assertNull($unpaid->bill_id);
        $this->assertFalse($unpaid->isPaid());
    }

    public function testGetItemsModelsBackfillsInstallmentPlanIdWhenMissing(): void
    {
        $plan = new InstallmentPlan([
            'id' => 42,
            'items' => [
                [
                    'id' => 1,
                    'no' => 1,
                    'month' => '2026-04-01 00:00:00',
                    'sum' => '3.50',
                    'currency' => 'eur',
                ],
            ],
        ]);

        $items = $plan->getItemsModels();

        $this->assertCount(1, $items);
        $this->assertSame(42, $items[0]->installment_plan_id);
    }

    public function testGetItemsModelsHandlesStdClassItems(): void
    {
        $item = new \stdClass();
        $item->id = 10;
        $item->no = 2;
        $item->month = '2026-05-01 00:00:00';
        $item->sum = '7.00';
        $item->currency = 'eur';
        $item->charge_id = null;

        $plan = new InstallmentPlan([
            'id' => 777,
            'items' => [$item],
        ]);

        $items = $plan->getItemsModels();

        $this->assertCount(1, $items);
        $this->assertInstanceOf(InstallmentPlanItem::class, $items[0]);
        $this->assertSame(777, $items[0]->installment_plan_id);
        $this->assertSame(2, $items[0]->no);
    }
}
