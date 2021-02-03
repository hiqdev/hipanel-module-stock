<?php

namespace hipanel\modules\stock\widgets;

use hipanel\modules\stock\models\HardwareSettings;
use yii\base\Widget;
use yii\widgets\ActiveForm;

class HardwareSettingsForm extends Widget
{
    public HardwareSettings $model;

    public function run(): string
    {
        return $this->render('HardwareSettingsForm', ['model' => $this->model]);
    }

    public function field(ActiveForm $form, string $type, string $attribute): string
    {
        $model = $this->model;
        $transform = static fn(string $attr): string => "props[$type][$attribute]";
        if (in_array($attribute, ['cores', 'threads', 'max_ram_size', 'ram_slots', 'cpu_sockets'], true)) {
            return $form->field($model, $transform($attribute))->input('number')->label($model->getAttributeLabel($attribute));
        }
        if ($attribute === "formfactor") {
            $type = mb_strtoupper($type);

            return $form->field($model, $transform($attribute))->dropDownList([
                '2.5"' => $type . ' 2.5"',
                '3.5"' => $type . '3.5"',
            ], ['prompt' => '--'])->label($model->getAttributeLabel($attribute));
        }

        return $form->field($model, $transform($attribute))->textInput(['value' => $model->props[$type][$attribute]])->label($model->getAttributeLabel($attribute));
    }
}
