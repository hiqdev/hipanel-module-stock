<?php
declare(strict_types=1);

namespace hipanel\modules\stock\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use hipanel\modules\stock\assets\Mobile\MobileAppAsset;

class MobileAppWidget extends Widget
{
    public function run(): string
    {
        MobileAppAsset::register($this->view);
        $assetManager = Yii::$app->assetManager;
        if (is_dir(Yii::getAlias('@advancedhosting/asset/advancedhosting'))) {
            $logo = Yii::getAlias('@advancedhosting/asset/advancedhosting/assets/images/logo_white_login.svg');
            $scannerPlain = Yii::getAlias('@advancedhosting/asset/advancedhosting/assets/images/wws250i.png');
            [, $logoSrc] = $assetManager->publish($logo);
            [, $scannerPlainSrc] = $assetManager->publish($scannerPlain);
        }
        $this->view->registerJsVar('__logoSrc', $logoSrc ?? '');
        $this->view->registerJsVar('__scannerSrc', $scannerPlainSrc ?? '');

        return Html::tag('div', Yii::t('hipanel:stock', 'Loading...'), [
            'id' => 'mobile-app',
        ]);
    }
}
