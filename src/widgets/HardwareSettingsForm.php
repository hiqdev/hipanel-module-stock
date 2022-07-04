<?php

namespace hipanel\modules\stock\widgets;

use hipanel\modules\stock\models\HardwareSettings;
use yii\base\Widget;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

class HardwareSettingsForm extends Widget
{
    public HardwareSettings $model;

    public const FORM_FACTOR =
    [
        'hdd' => [
            '2.5"' => 'HDD 2.5"',
            '3.5"' => 'HDD 3.5"',
        ],
        'ssd' => [
            '2.5"' => 'SSD 2.5"',
            'HHHL' => 'SSD HHHL',
            'M.2' => 'SSD M.2',
        ],
    ];

    public const INTERFACE = [
        'hdd' => [
            'SATA' => 'SATA',
            'SAS' => 'SAS',
            'NVMe' => 'NVMe',
            'PCIe' => 'PCIe',
            'M.2' => 'M.2',
        ],
        'ssd' => [
            'SATA' => 'SATA',
            'SAS' => 'SAS',
            'NVMe' => 'NVMe',
            'PCIe' => 'PCIe',
            'M.2' => 'M.2',
        ],
    ];

    public const TYPES =
    [
        'hdd' => [
            '240GB' => '240GB',
            '480GB' => '480GB',
            '960GB' => '960GB',
            '1.92TB' => '1.92TB',
            '3.84TB' => '3.84TB',
            '7.68TB' => '7.68TB',
            '1TB' => '1TB',
            '2TB' => '2TB',
            '4TB' => '4TB',
            '8TB' => '8TB',
        ],
        'ssd' => [
            '240GB' => '240GB',
            '480GB' => '480GB',
            '960GB' => '960GB',
            '1.92TB' => '1.92TB',
            '3.84TB' => '3.84TB',
            '7.68TB' => '7.68TB',
            '1TB' => '1TB',
            '2TB' => '2TB',
            '4TB' => '4TB',
            '8TB' => '8TB',
        ],
        'ram' => [
            '8GB' => '8GB',
            '16GB' => '16GB',
            '32GB' => '32GB',
            '64GB' => '64GB',
            '128GB' => '128GB',
        ],
    ];

    public const UNITS_QTY =
    [
        'chassis' => [
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
        ],
        'server' => [
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
        ],
    ];

    public const HDD_QTY_25 =
    [
        'chassis' => [
            '2' => '2',
            '4' => '4',
            '6' => '6',
            '8' => '8',
            '10' => '10',
            '12' => '12',
            '24' => '24',
        ],
        'server' => [
            '2' => '2',
            '4' => '4',
            '6' => '6',
            '8' => '8',
            '10' => '10',
            '12' => '12',
            '24' => '24',
        ],
    ];

    public const HDD_QTY_35 =
    [
        'chassis' => [
            '2' => '2',
            '4' => '4',
            '6' => '6',
            '8' => '8',
            '10' => '10',
            '12' => '12',
            '24' => '24',
            '36' => '36',
            '60' => '60',
            '90' => '90',
        ],
        'server' => [
            '2' => '2',
            '4' => '4',
            '6' => '6',
            '8' => '8',
            '10' => '10',
            '12' => '12',
            '24' => '24',
            '36' => '36',
            '60' => '60',
            '90' => '90',
        ],
    ];

    public function run(): string
    {
        return $this->render('HardwareSettingsForm', ['model' => $this->model]);
    }

    public function field(ActiveForm $form, string $type, string $attribute): string
    {
        $transform = static fn(string $attr): string => "props[$type][$attribute]";
        switch ($attribute) {
            case 'cores':
            case 'threads':
            case 'max_ram_size':
            case 'ram_slots':
            case 'cpu_sockets':
                return $this->getInputNumber($form, $transform, $attribute);
            case 'interface':
                return $this->getInterface($form, $type, $transform, $attribute);
            case 'formfactor':
                return $this->getFormFactor($form, $type, $transform, $attribute);
            case 'frequency':
                return $this->getFrequency($form, $type, $transform, $attribute);
            case 'ports_quantity':
                return $this->getPortsQuantity($form, $type, $transform, $attribute);
            case 'ports_speed':
                return $this->getPortsSpeed($form, $type, $transform, $attribute);
            case 'port_type':
                return $this->getPortType($form, $type, $transform, $attribute);
            case 'size':
                return $this->getSize($form, $type, $transform, $attribute);
            case 'type':
                return $this->getType($form, $type, $transform, $attribute);
            case 'units_qty':
                return $this->getUnitsQty($form, $type, $transform, $attribute);
            case '25_hdd_qty':
                return $this->getHddQty25($form, $type, $transform, $attribute);
            case '35_hdd_qty':
                return $this->getHddQty35($form, $type, $transform, $attribute);
            default:
                return $this->getDefaultField($form, $type, $transform, $attribute);
        }
    }

    private function getDefaultField(ActiveForm $form, string $type, \Closure $transform, string $attribute): ActiveField
    {
        return $form->field($this->model, $transform($attribute))
            ->textInput(['value' => $this->model->props[$type][$attribute]])
            ->label($this->model->getAttributeLabel($attribute));
    }

    private function getFormFactor(ActiveForm $form, string $type, \Closure $transform, string $attribute): ActiveField
    {
        if (isset(self::FORM_FACTOR[$type])) {
            return $form->field($this->model, $transform($attribute))
                ->dropDownList(self::FORM_FACTOR[$type], ['prompt' => '--'])
                ->label($this->model->getAttributeLabel($attribute));
        } else {
            return  $this->getDefaultField($form, $type, $transform, $attribute);
        }
    }

    private function getFrequency(ActiveForm $form, string $type, \Closure $transform, string $attribute): ActiveField
    {
        if ($type === 'ram') {
            return $form->field($this->model, $transform($attribute))
                ->dropDownList(
                    [
                        '2133' => '2133 MHz',
                        '2400' => '2400 MHz',
                        '2666' => '2666 MHz',
                        '2933' => '2933 MHz',
                        '3200' => '3200 MHz',
                    ],
                    [
                        'prompt' => '--'
                    ]
                )
                ->label($this->model->getAttributeLabel($attribute));
        } else {
            return $this->getDefaultField($form, $type, $transform, $attribute);
        }
    }

    private function getInputNumber(ActiveForm $form, \Closure $transform, string $attribute): ActiveField
    {
        return $form->field($this->model, $transform($attribute))
            ->input('number')
            ->label($this->model->getAttributeLabel($attribute));
    }

    private function getInterface(ActiveForm $form, string $type, \Closure $transform, string $attribute): ActiveField
    {
        if (isset(self::INTERFACE[$type])) {
            return $form->field($this->model, $transform($attribute))
                ->dropDownList(self::INTERFACE[$type], ['prompt' => '--'])
                ->label($this->model->getAttributeLabel($attribute));
        } else {
            return  $this->getDefaultField($form, $type, $transform, $attribute);
        }
    }

    private function getHddQty25(ActiveForm $form, string $type, \Closure $transform, string $attribute): ActiveField
    {
        if (isset(self::HDD_QTY_25[$type])) {
            return $form->field($this->model, $transform($attribute))
                ->dropDownList(self::HDD_QTY_25[$type], ['prompt' => '--'])
                ->label($this->model->getAttributeLabel($attribute));
        } else {
            return  $this->getDefaultField($form, $type, $transform, $attribute);
        }
    }

    private function getHddQty35(ActiveForm $form, string $type, \Closure $transform, string $attribute): ActiveField
    {
        if (isset(self::HDD_QTY_35[$type])) {
            return $form->field($this->model, $transform($attribute))
                ->dropDownList(self::HDD_QTY_35[$type], ['prompt' => '--'])
                ->label($this->model->getAttributeLabel($attribute));
        } else {
            return  $this->getDefaultField($form, $type, $transform, $attribute);
        }
    }

    private function getPortsQuantity(ActiveForm $form, string $type, \Closure $transform, string $attribute): ActiveField
    {
        if ($type === 'net_adapter') {
            return $form->field($this->model, $transform($attribute))
                ->dropDownList(
                    [
                        '1' => '1',
                        '2' => '2',
                        '4' => '4',
                    ],
                    [
                        'prompt' => '--'
                    ]
                )
                ->label($this->model->getAttributeLabel($attribute));
        } else {
            return $this->getDefaultField($form, $type, $transform, $attribute);
        }
    }

    private function getPortsSpeed(ActiveForm $form, string $type, \Closure $transform, string $attribute): ActiveField
    {
        if ($type === 'net_adapter') {
            return $form->field($this->model, $transform($attribute))
                ->dropDownList(
                    [
                        '1G' => '1G',
                        '10G' => '10G',
                        '10G/25G' => '10G/25G',
                        '25G' => '25G',
                        '40G' => '40G',
                        '56G' => '56G',
                        '100G' => '100G',
                    ],
                    [
                        'prompt' => '--'
                    ]
                )
                ->label($this->model->getAttributeLabel($attribute));
        } else {
            return $this->getDefaultField($form, $type, $transform, $attribute);
        }
    }

    private function getPortType(ActiveForm $form, string $type, \Closure $transform, string $attribute): ActiveField
    {
        if ($type === 'net_adapter') {
            return $form->field($this->model, $transform($attribute))
                ->dropDownList(
                    [
                        'RJ45' => 'RJ45',
                        'SFP' => 'SFP',
                        'SFP+' => 'SFP+',
                        'SFP+/SFP28' => 'SFP+/SFP28',
                        'SFP28' => 'SFP28',
                        'QSFP+' => 'QSFP+',
                        'QSFP28' => 'QSFP28',
                        'Infiniband' => 'Infiniband',
                    ],
                    [
                        'prompt' => '--'
                    ]
                )
                ->label($this->model->getAttributeLabel($attribute));
        } else {
            return $this->getDefaultField($form, $type, $transform, $attribute);
        }
    }

    private function getSize(ActiveForm $form, string $type, \Closure $transform, string $attribute): ActiveField
    {
        if (isset(self::TYPES[$type])) {
            return $form->field($this->model, $transform($attribute))
                ->dropDownList(self::TYPES[$type], ['prompt' => '--'])
                ->label($this->model->getAttributeLabel($attribute));
        } else {
            return  $this->getDefaultField($form, $type, $transform, $attribute);
        }
    }

    private function getType(ActiveForm $form, string $type, \Closure $transform, string $attribute): ActiveField
    {
        if ($type === 'ram') {
            return $form->field($this->model, $transform($attribute))
                ->dropDownList(
                    [
                        'DDR3' => 'DDR3',
                        'DDR4' => 'DDR4',
                        'DDR5' => 'DDR5',
                    ],
                    [
                        'prompt' => '--'
                    ]
                )
                ->label($this->model->getAttributeLabel($attribute));
        } else {
            return $this->getDefaultField($form, $type, $transform, $attribute);
        }
    }

    private function getUnitsQty(ActiveForm $form, string $type, \Closure $transform, string $attribute): ActiveField
    {
        if (isset(self::UNITS_QTY[$type])) {
            return $form->field($this->model, $transform($attribute))
                ->dropDownList(self::UNITS_QTY[$type], ['prompt' => '--'])
                ->label($this->model->getAttributeLabel($attribute));
        } else {
            return  $this->getDefaultField($form, $type, $transform, $attribute);
        }
    }
}
