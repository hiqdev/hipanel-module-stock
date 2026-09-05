<?php

namespace hipanel\modules\stock\models;

use hipanel\base\Model;
use Yii;

/**
 *
 * @property-read array $activeAttributes
 */
class HardwareSettings extends Model
{
    public static function tableName(): string
    {
        return 'model';
    }

    public function rules(): array
    {
        return [
            [['id', 'average_power_consumption'], 'integer', 'on' => ['set', 'get']],
            [['model_type'], 'string', 'on' => ['set', 'get']],
            [['props'], 'safe'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'formfactor' => Yii::t('hipanel:stock', 'Form factor'),
            'average_power_consumption' => Yii::t('hipanel:stock', 'Estimated average power consumption in W'),
            'max_ram_size' => Yii::t('hipanel:stock', 'Max RAM'),
            'ram_slots' => Yii::t('hipanel:stock', 'RAM slots'),
            'cpu_sockets' => Yii::t('hipanel:stock', 'CPU sockets'),
            'units_qty' => Yii::t('hipanel:stock', 'Units'),
            '25_hdd_qty' => Yii::t('hipanel:stock', '2.5" HDD'),
            '35_hdd_qty' => Yii::t('hipanel:stock', '3.5" HDD'),
            'ram_qty' => Yii::t('hipanel:stock', 'RAM slots'),
            'cpu_qty' => Yii::t('hipanel:stock', 'CPU quantity'),
            'size' => Yii::t('hipanel:stock', 'Size'),
        ];
    }

    public function scenarioActions(): array
    {
        return [
            'get' => 'get-hardware-settings',
            'set' => 'set-hardware-settings',
        ];
    }

    public function getActiveAttributes(): array
    {
        return array_filter($this->attributes, fn($value, $attribute): bool => $this->isAttributeActive($attribute), ARRAY_FILTER_USE_BOTH);
    }

    public function hasAttributes(): bool
    {
        $attributes = $this->getActiveAttributes();

        return !empty($attributes);
    }
}
