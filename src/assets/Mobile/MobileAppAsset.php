<?php
declare(strict_types=1);

namespace hipanel\modules\stock\assets\Mobile;

use yii\web\AssetBundle;

class MobileAppAsset extends AssetBundle
{
    public $sourcePath = __DIR__;
    public $baseUrl = '/stock/mobile/index';
    public $publishOptions = ['forceCopy' => true];
    public $js = ['dist/mobile-app.js'];
}
