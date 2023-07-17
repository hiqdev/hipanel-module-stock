<?php
declare(strict_types=1);

namespace hipanel\modules\stock\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class MobileAppWidget extends Widget
{
    public function run(): string
    {
        return Html::tag('div', Yii::t('hipanel:stock', 'Loading...'), ['id' => 'mobile-app']);
    }
}
