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

use hipanel\base\Model;
use hipanel\base\ModelTrait;
use hipanel\helpers\ArrayHelper;
use hipanel\helpers\StringHelper;
use Yii;

class Part extends Model
{
    use ModelTrait;

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
                'model',
                'create_time',
                'place',
                'move_type',
                'move_type_label',
                'move_time',
                'move_remote_ticket',
                'move_hm_ticket',
                'move_remotehands_label',
                'show_deleted',
                'show_groups',
            ], 'safe', 'on' => 'search'],
            // Create
            [[
                'partno',
                'model_id',
                'serials',
                'descr',
                'price',
                'currency',
                'client',
                'client_id',
                'supplier',
                'order_no',
            ], 'safe', 'on' => ['create']],
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
            ], 'safe', 'on' => ['move', 'bulk-move']],
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
            'model_type'        => Yii::t('app', 'Type'),
            'create_time'       => Yii::t('app', 'Created'),
            'move_time'         => Yii::t('app', 'Moved'),
            'move_type_label'   => Yii::t('app', 'Move type'),
            'move_descr'        => Yii::t('app', 'Move description'),
            'move_type'         => Yii::t('app', 'Type'),
            'order_data'        => Yii::t('app', 'Order'),
            'order_no'          => Yii::t('app', 'Order No.'),
            'src_id'            => Yii::t('app', 'Source'),
            'dst_id'            => Yii::t('app', 'Destination'),
            'src_name'          => Yii::t('app', 'Source'),
            'dst_name'          => Yii::t('app', 'Destination'),
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
}
