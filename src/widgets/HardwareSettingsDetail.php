<?php

namespace hipanel\modules\stock\widgets;

use yii\base\Widget;
use yii\helpers\Url;
use yii\web\View;

class HardwareSettingsDetail extends Widget
{
    public int $id;

    public string $type;

    public array $props = [];

    public function run(): string
    {
        $url = Url::to(['@model/hardware-settings', 'id' => $this->id, 'type' => $this->type]);
        $this->view->registerJs("$.ajax({
           type: 'POST',
           url: '{$url}',
           success: html => {
             $('#{$this->id}').html(html);
           }
        });", View::POS_LOAD);

        return $this->render('HardwareSettingsDetail', ['props' => $this->props]);
    }
}
