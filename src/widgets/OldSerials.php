<?php

declare(strict_types=1);

namespace hipanel\modules\stock\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class OldSerials extends Widget
{
    /** @var array<array{serial: string, since: string, till: ?string}> */
    public array $history = [];

    public function run(): string
    {
        $lines = [];
        foreach ($this->history as $period) {
            if (empty($period['till'])) {
                continue;
            }
            $lines[] = Html::encode(Yii::t('hipanel:stock', 'from {since} to {till}: {serial}', [
                'since' => Yii::$app->formatter->asDatetime($period['since']),
                'till' => Yii::$app->formatter->asDatetime($period['till']),
                'serial' => $period['serial'],
            ]));
        }

        return implode('<br>', $lines);
    }
}
