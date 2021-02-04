<?php

namespace hipanel\modules\stock\widgets;

use hipanel\widgets\AjaxModalWithTemplatedButton;
use Yii;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\Html;

class HardwareSettingsButton extends Widget
{
    public int $id;

    public string $type;

    public function run(): string
    {
        return AjaxModalWithTemplatedButton::widget([
            'ajaxModalOptions' => [
                'id' => "hardware-settings-modal-{$this->id}",
                'bulkPage' => false,
                'header' => Html::tag('h4', Yii::t('hipanel:stock', 'Hardware properties'), ['class' => 'modal-title']),
                'scenario' => 'default',
                'actionUrl' => $this->getUrl(),
                'size' => Modal::SIZE_LARGE,
                'handleSubmit' => $this->getUrl(),
                'toggleButton' => [
                    'tag' => 'a',
                    'label' => Html::tag('i', null, ['class' => 'fa fa-fw fa-cogs pull-right']) . Yii::t('hipanel:stock', 'Hardware properties'),
                    'style' => 'cursor: pointer;',
                ],
            ],
            'toggleButtonTemplate' => '{toggleButton}',
        ]);
    }

    private function getUrl(): array
    {
        return ['@model/hardware-settings', 'id' => $this->id, 'type' => $this->type];
    }
}
