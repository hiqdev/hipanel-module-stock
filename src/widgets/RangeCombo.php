<?php

namespace hipanel\modules\stock\widgets;

use hipanel\modules\stock\widgets\combo\PartDestinationCombo;
use yii\base\Widget;
use yii\db\ActiveRecordInterface;

class RangeCombo extends Widget
{
    public string $name;

    public string $attribute;

    public bool $useDstTypes = true;

    public ActiveRecordInterface $model;

    public function run(): string
    {
        $options = array_filter([
            'name' => $this->name,
            'model' => $this->model,
            'attribute' => $this->attribute,
            'type' => 'stock/' . $this->model->formName(),
            'multiple' => true,
            'primaryFilter' => 'name_inilike',
            'pluginOptions' => [
                'select2Options' => ['placeholder' => $this->model->getAttributeLabel($this->attribute)],
            ],
        ]);
        if (!$this->useDstTypes) {
            $options['filter'] = [];
        }

        return PartDestinationCombo::widget($options);
    }
}
