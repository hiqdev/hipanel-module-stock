<?php

declare(strict_types=1);

namespace hipanel\modules\stock\widgets;

use Yii;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class WarrantyMonthsRangeInput extends InputWidget
{
    public ?string $hint = null;

    public function run(): string
    {
        $widgetId = $this->getId();
        $inputId = $this->options['id'];
        if (str_contains((string)$this->model->{$this->attribute}, ',')) {
            [$from, $till] = explode(',', $this->model->{$this->attribute});
        } else {
            $this->model->{$this->attribute} = null;
        }
        $hiddenInput = $this->hasModel()
            ? Html::activeHiddenInput($this->model, $this->attribute, $this->options)
            : Html::hiddenInput($this->name, $this->value, $this->options);
        $fromInput = $this->createInput('warranty_from', Yii::t('hipanel:stock', 'Warranty from'), $from ?? '');
        $tillInput = $this->createInput('warranty_till', Yii::t('hipanel:stock', 'Warranty till'), $till ?? '');
        $hint = Yii::t('hipanel:stock', "Enter a range of months in numeric format, where 0 is the current month, -1 the previous month and 1 the next month.");
        $this->view->registerJs(/** @lang JavaScript */ <<<"JS"
          ;(() => {
            const fromInput = \$("#$widgetId input[name$='from']");
            const tillInput = \$("#$widgetId input[name$='till']");
            \$("#$widgetId input[type!='hidden']").on("change", function (event) {
              const from = fromInput.val();
              const till = tillInput.val();
              const value = [from, till];
             \$("#$inputId").val(value.join(",", value));
            });
          })();

JS
        );

        return <<<HTML
            <div id="$widgetId" style="display: flex;">
                $fromInput
                <span style="display: inline-block; width: 24px; line-height: 32px; text-align: center;">-</span>
                $tillInput
            </div>
            <div style="color: rgba(0, 0, 0, 0.45)">$hint</div>
            $hiddenInput
HTML;
    }

    private function createInput(string $attribute, string $label, string $value): string
    {
        return Html::input('number', $attribute, $value, [
            'class' => 'form-control',
            'placeholder' => $label,
            'min' => -999,
            'max' => 999,
            'step' => 1,
            'style' => [
                'width' => 'calc(50% - 12px)',
            ],
        ]);
    }
}
