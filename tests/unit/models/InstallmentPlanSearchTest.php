<?php

declare(strict_types=1);

namespace hipanel\modules\stock\tests\unit\models;

use hipanel\modules\stock\models\InstallmentPlanSearch;
use PHPUnit\Framework\TestCase;

final class InstallmentPlanSearchTest extends TestCase
{
    public function testSellerIdPropertyAndSearchAttributeAreAvailable(): void
    {
        $model = new InstallmentPlanSearch();

        $this->assertTrue($model->canGetProperty('seller_id', true, true));
        $this->assertContains('seller_id', $model->searchAttributes());
    }
}

