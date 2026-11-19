<?php

declare(strict_types=1);

namespace hipanel\modules\stock\tests\unit\models;

use hipanel\modules\stock\models\Move;
use PHPUnit\Framework\TestCase;

final class MoveTest extends TestCase
{
    private const int ORDER_ID = 10;
    private const string ORDER_NAME = 'Supplier order';
    private const int OTHER_ORDER_ID = 20;
    private const string OTHER_ORDER_NAME = 'Other supplier order';

    public function testOrdersAreTheDistinctOrdersOfTheMoveParts(): void
    {
        $move = $this->givenMoveWithParts([
            ['order_id' => self::ORDER_ID, 'order_name' => self::ORDER_NAME],
            ['order_id' => self::ORDER_ID, 'order_name' => self::ORDER_NAME],
            ['order_id' => self::OTHER_ORDER_ID, 'order_name' => self::OTHER_ORDER_NAME],
        ]);

        $orders = $move->getOrders();

        $this->assertSame([
            self::ORDER_ID => self::ORDER_NAME,
            self::OTHER_ORDER_ID => self::OTHER_ORDER_NAME,
        ], $orders);
    }

    public function testPartsWithoutOrderGiveNoOrders(): void
    {
        $move = $this->givenMoveWithParts([
            ['order_id' => null],
            [],
        ]);

        $orders = $move->getOrders();

        $this->assertSame([], $orders);
    }

    private function givenMoveWithParts(array $parts): Move
    {
        $move = new Move();
        $move->parts = $parts;

        return $move;
    }
}
