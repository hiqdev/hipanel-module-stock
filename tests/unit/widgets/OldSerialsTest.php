<?php

declare(strict_types=1);

namespace hipanel\modules\stock\tests\unit\widgets;

use hipanel\modules\stock\widgets\OldSerials;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\console\Application;
use yii\i18n\PhpMessageSource;

final class OldSerialsTest extends TestCase
{
    protected function setUp(): void
    {
        new Application([
            'id' => 'old-serials-test',
            'basePath' => __DIR__,
            'language' => 'en-US',
            'components' => [
                'formatter' => [
                    'datetimeFormat' => 'php:d.m.Y H:i',
                    'timeZone' => 'UTC',
                    'defaultTimeZone' => 'UTC',
                ],
                'i18n' => [
                    'translations' => [
                        'hipanel:stock' => ['class' => PhpMessageSource::class, 'sourceLanguage' => 'en-US'],
                    ],
                ],
            ],
        ]);
    }

    protected function tearDown(): void
    {
        Yii::$app->errorHandler->unregister();
        Yii::$app = null;
    }

    public function testListsOnlyClosedPeriodsWithTheirDates(): void
    {
        $html = OldSerials::widget(['history' => [
            ['serial' => 'OLD-1', 'since' => '2024-01-01 10:00:00', 'till' => '2025-05-15 12:30:00'],
            ['serial' => 'OLD-2', 'since' => '2025-05-15 12:30:00', 'till' => '2025-06-01 08:00:00'],
            ['serial' => 'CURRENT', 'since' => '2025-06-01 08:00:00', 'till' => null],
        ]]);

        $this->assertSame(
            'from 01.01.2024 10:00 to 15.05.2025 12:30: OLD-1<br>from 15.05.2025 12:30 to 01.06.2025 08:00: OLD-2',
            $html,
        );
    }

    public function testRendersNothingForAPartThatNeverChangedItsSerial(): void
    {
        $html = OldSerials::widget(['history' => [
            ['serial' => 'CURRENT', 'since' => '2024-01-01 10:00:00', 'till' => null],
        ]]);

        $this->assertSame('', $html);
    }

    public function testEscapesTheSerial(): void
    {
        $html = OldSerials::widget(['history' => [
            ['serial' => '<b>X</b>', 'since' => '2024-01-01 10:00:00', 'till' => '2025-01-01 10:00:00'],
        ]]);

        $this->assertStringContainsString('&lt;b&gt;X&lt;/b&gt;', $html);
    }
}
