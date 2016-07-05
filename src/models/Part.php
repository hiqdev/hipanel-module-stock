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

use hipanel\base\ModelTrait;
use hipanel\helpers\StringHelper;
use Yii;

class Part extends \hipanel\base\Model
{
    use ModelTrait;

    /** @inheritdoc */
    public static $i18nDictionary = 'hipanel/stock';

    public function rules()
    {
        return [
            // Search
            [[
                'model_type_label',
                'model_type',
                'model_brand',
                'model_brand_label',
                'src_name',
                'dst_name',
                'order_data',

                'id',
                'dst_ids',
                'model_ids',
                'serial',
                'serials',
                'partno',
                'create_time',
                'place',
                'count',
                'move_type',
                'move_type_label',
                'move_time',
                'move_descr',
                'move_remote_ticket',
                'move_hm_ticket',
                'move_remotehands_label',
                'show_deleted',
                'show_groups',
            ], 'safe', 'on' => 'search'],
            // Move by one
            [[
                'id',
                'move_type',
                'src_id',
                'dst_id',
                'descr',
                'remotehands',
                'remote_ticket',
                'hm_ticket',
            ], 'safe', 'on' => 'move-by-one'],
            // Create
            [[
                'partno',
                'model_id',
                'serials',
                'move_descr',
                'price',
                'currency',
                'client',
                'client_id',
                'supplier',
                'order_no',
                'move_type',
                'src_id',
                'dst_id',
            ], 'safe', 'on' => ['create', 'copy']],
            [['serials', 'src_id'], 'required', 'on' => 'copy'],
            // Trash
            [[
                'id',
                'src_id',
                'dst_id',
                'serial',
                'partno',
                'move_type',
                'move_descr',
            ], 'safe', 'on' => 'trash'],
            // Replace
            [[
                'id',
                'move_descr',
                'model_type',
                'supplier',
                'order_no',
                'dst_id',
                'src_id',
                'serial',
                'move_type',
            ], 'safe', 'on' => 'replace'],
            [['id'], 'required', 'on' => 'replace'],
            [[
                'src_id',
                'dst_id',
                'move_type',
            ], 'required', 'on' => ['replace']],
            // Repair
            [[
                'id',
                'move_descr',
                'model_type',
                'supplier',
                'order_no',
                'dst_id',
                'src_id',
                'serial',
                'move_type',
            ], 'safe', 'on' => 'repair'],
            [['id'], 'required', 'on' => 'repair'],
            [[
                'src_id',
                'dst_id',
                'move_type',
            ], 'required', 'on' => ['create']],
            // Update
            [[
                'id',
                'serial',
                'price',
                'currency',
            ], 'safe', 'on' => ['update']],
            // Move / Bulk-move
            [[
                'id',
                'src_id',
                'dst_id',
                'type',
                'descr',
                'remotehands',
                'remote_ticket',
                'hm_ticket',
                'parts',
            ], 'safe', 'on' => ['move']],
            [[
                'src_id',
                'dst_id',
                'type',
            ], 'required', 'on' => ['move']],

            // Reserve / Unreserve
            [[
                'id',
                'reserve',
                'descr',
            ], 'safe', 'on' => ['reserve', 'unreserve']],

            // Bulk set price
            [['id', 'price'], 'required', 'on' => ['set-price']],
            [['currency'], 'safe', 'on' => ['set-price']],

            // Set serial
            [['id', 'serial'], 'required', 'on' => ['set-serial']],
            [['serial'], 'filter', 'filter' => 'trim', 'on' => ['set-serial']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'partno'            => Yii::t('app', 'Part No.'),
            'model_brand'       => Yii::t('app', 'Manufacturer'),
            'model_brand_label' => Yii::t('app', 'Manufacturer'),
            'model_type'        => Yii::t('app', 'Type'),
            'model_type_label'  => Yii::t('app', 'Type'),
            'create_time'       => Yii::t('app', 'Created'),
            'create_date'       => Yii::t('app', 'Created'),
            'move_date'         => Yii::t('hipanel/stock', 'Moved'),
            'move_time'         => Yii::t('hipanel/stock', 'Moved'),
            'move_type_label'   => Yii::t('app', 'Move type'),
            'move_descr'        => Yii::t('app', 'Move description'),
            'move_type'         => Yii::t('app', 'Type'),
            'order_data'        => Yii::t('app', 'Order'),
            'order_no'          => Yii::t('app', 'Order No.'),
            'src_id'            => Yii::t('hipanel/stock', 'Source'),
            'src_name'          => Yii::t('hipanel/stock', 'Source'),
            'dst_id'            => Yii::t('hipanel/stock', 'Destination'),
            'dst_name'          => Yii::t('hipanel/stock', 'Destination'),
            'supplier'          => Yii::t('hipanel/stock', 'Supplier'),
        ]);
    }

    public function transformToSymbols($currencyCodes = [])
    {
        $result = [];
        foreach ($currencyCodes as $code) {
            $result[] = StringHelper::getCurrencySymbol($code);
        }
        return $result;
    }

    public function getModel()
    {
        return $this->hasOne(Model::class, ['id' => 'model_id']);
    }

    /**
     * Group all results by dst_id.
     *
     * @param array $models
     * @return array
     */
    public static function groupMoveBulkElements(array $models = [])
    {
        $grouped_models = [];
//        foreach ($models as $k => $model) {
//            if (!$model->dst_id) {
//                $model->dst_id = ArrayHelper::getValues($model, ['src_id', 'dst_id', 'src_name', 'dst_name']);
//            }
//            $grouped_models[$model['dst_id']]['parts'][$model->id] = [
//                'id'        => $model->id,
//                'serial'    => $model['serial'],
//                'partno'    => $model['partno']
//            ];
//            $grouped_models[$k] = $model;
//        }

        return $grouped_models;
    }

    public function scenarioCommands()
    {
        return [
            'repair' => 'move',
            'copy' => 'create',
            'trash' => 'move',
            'replace' => 'move',
            'move-by-one' => 'move',
        ];
    }

    /**
     * @param $types array
     * @param $scenario string
     * @return array
     */
    public function filterTypes($types, $scenario)
    {
        $result = [];
        $matches = [
            'repair' => ['repair', 'replace'],
            'replace' => ['replace', 'repair'],
            'copy' => ['order', 'direct', 'outdated'],
            'trash' => ['died', 'outdated'],
        ];
        
        if (key_exists($scenario, $matches)) {
            foreach ($matches[$scenario] as $key) {
                $result[$key] = $types[$key];
            }
        } else {
            $result = $types;
        }
        
        return $result;
    }
}
