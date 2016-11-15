<?php

/*
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\stock\models;

use hipanel\base\Model as YiiModel;
use hipanel\base\ModelTrait;
use Yii;
use yii\helpers\Html;

class Model extends YiiModel
{
    use ModelTrait;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // Search
            [[
                'id',
                'url',
                'descr',
                'short',
                'is_favourite',
                'profile',
                'profile_id',
                'last_prices',
                'model',
                'type',
                'types',
                'type_label',
                'state',
                'states',
                'state_label',
                'brand',
                'brands',
                'brand_label',
                'tag',
                'tags',
                'tags_all',
                'prop_tag',
                'prop_tags',
                'prop_tags_all',
                'partno',
                'with_counters',
                'with_prices',
                'tariff_id',
                'show_system',
                'show_hidden_from_user',
                'dcs',
                'counters',

                'dtg',
                'sdg',
                'm3',
            ], 'safe'],
            // Create & Update
            [[
                'id',
                'type',
                'partno',
                'brand',
                'model',
                'url',
                'descr',
                'short',
                'is_favourite',
                'profile',
                'tags',
                'prop_tags',
                'props',
                // Chassis
                'UNITS_QTY',
                '35_HDD_QTY',
                '25_HDD_QTY',
                // Server
                'units_qty',
                '35_hdd_qty',
                '25_hdd_qty',
                'ram_qty',
                'cpu_qty',
                // CPU
                // HDD
                'FORMFACTOR',
                // Motherboard
                'RAM_AMOUNT',
                'RAM_QTY',
                'CPU_QTY',
                // RAM
                'RAM_VOLUME',
            ], 'safe', 'on' => ['create', 'update']],
            [[
                'type',
                'brand',
                'model',
                'partno',
            ], 'required', 'on' => ['create']],
            [[
                'model',
                'partno',
            ], 'required', 'on' => ['update']],
            // Hide & Show
            ['id', 'required', 'on' => ['mark-hidden-from-user', 'unmark-hidden-from-user']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'partno' => Yii::t('hipanel:stock', 'Part No.'),
            'show_hidden_from_user' => Yii::t('hipanel:stock', 'Show hidden'),
            'profile' => Yii::t('hipanel:stock', 'Group'),
            // Chassis
            'UNITS_QTY' => Yii::t('hipanel:stock', 'Units'),
            '35_HDD_QTY' => Yii::t('hipanel:stock', '3.5" HDD'),
            '25_HDD_QTY' => Yii::t('hipanel:stock', '2.5" HDD'),
            // Server
            'units_qty' => Yii::t('hipanel:stock', 'Units'),
            '35_hdd_qty' => Yii::t('hipanel:stock', '3.5" HDD'),
            '25_hdd_qty' => Yii::t('hipanel:stock', '2.5" HDD'),
            'ram_qty' => Yii::t('hipanel:stock', 'RAM slots'),
            'cpu_qty' => Yii::t('hipanel:stock', 'CPU quantity'),
            // CPU
            'prop_tags' => Yii::t('hipanel:stock', 'Tags'),
            // HDD
            'FORMFACTOR' => Yii::t('hipanel:stock', 'Form factor'),
            // Motherboard
            'RAM_AMOUNT' => Yii::t('hipanel:stock', 'Max RAM'),
            'RAM_QTY' => Yii::t('hipanel:stock', 'RAM slots'),
            'CPU_QTY' => Yii::t('hipanel:stock', 'CPU sockets'),
            // RAM
            'RAM_VOLUME' => Yii::t('hipanel:stock', 'RAM volume'),
            'dcs' => Yii::t('hipanel:stock', 'DCS'),
            // ---
            'dtg' => Yii::t('hipanel:stock', 'USA Equinix DC10'),
            'sdg' => Yii::t('hipanel:stock', 'NL Amsterdam SDG'),
            'm3' => Yii::t('hipanel:stock', 'NL Amsterdam M3'),
            'brand' => Yii::t('hipanel:stock', 'Brand'),
            'model' => Yii::t('hipanel:stock', 'Model'),
            'model_types' => Yii::t('hipanel:stock', 'Model types'),
            'model_' => Yii::t('hipanel:stock', 'Model types'),
        ]);
    }

//    public static function getDcs()
//    {
//        return Ref::getList('type,dc');
//    }

    public function getDcs($dc)
    {
        $out = '';
        if ($this['counters'][$dc]['rma']) {
            $out .= Html::tag('span', $this['counters'][$dc]['rma'], ['class' => 'text-danger']);
            // '<span style="color: red">' . $this['counters'][$dc]['rma'] . '</span>/';
        }
        $stock = $this['counters'][$dc]['stock'] - $this['counters'][$dc]['reserved'];
        $out .= $stock >= 0 ? $stock : 0;
        if ($this['counters'][$dc]['reserved']) {
            $out .= Html::tag('span', '+ ' . $this['counters'][$dc]['reserved'], ['class' => 'text-info']);
            //echo '+<span style="color: blue">' . $this['counters'][$dc]['reserved'] . '</span>';
        }
        return $out;
    }

    public function showModelPrices($data, $delimiter = ' / ')
    {
        $prices = [];
        if (is_array($data)) {
            foreach ($data as $currency => $price) {
                $prices[] = Yii::$app->formatter->format($price, ['currency', $currency]);
            }
            return implode($delimiter, $prices);
        }
        return '';
    }
}
