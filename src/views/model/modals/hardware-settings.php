<?php

use hipanel\modules\stock\models\HardwareSettings;
use hipanel\modules\stock\widgets\HardwareSettingsForm;

/* @var HardwareSettings $model */

echo HardwareSettingsForm::widget(['model' => $model]);