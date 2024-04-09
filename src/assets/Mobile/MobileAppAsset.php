<?php

declare(strict_types=1);

namespace hipanel\modules\stock\assets\Mobile;

use hipanel\assets\MixAssetBundle;

class MobileAppAsset extends MixAssetBundle
{
    public $sourcePath = __DIR__ . '/dist';
    public $baseUrl = '/stock/mobile/index';
    public $publishOptions = ['forceCopy' => true];
}
