<?php

declare(strict_types=1);

namespace hipanel\modules\stock\tests\unit\models;

use hipanel\modules\stock\models\PartSearch;
use PHPUnit\Framework\TestCase;

final class PartSearchTest extends TestCase
{
    public function testOldSerialIsASearchAttribute(): void
    {
        $this->assertContains('old_serial_ilike', (new PartSearch())->searchAttributes());
    }
}
