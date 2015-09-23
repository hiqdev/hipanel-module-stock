<?php

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
                'move_type_label',
                'model_type_label',
                'model_brand_label',
                'src_name',
                'dst_name',
                'order_data',
                'move_time',
                'move_remote_ticket',
                'move_hm_ticket',
                'move_remotehands_label',
                'currency_label',

                'id',
                'dst_ids',
                'model_ids',
                'serial',
                'serials',
                'serial_like',
                'partno',
                'partno_like',
                'model_types',
                'model_brands',
                'brand',
                'model',
                'time',
                'place',
                'src_name_like',
                'dst_name_like',
                'move_descr_like',
                'order_data_like',
                'show_deleted',
                'show_groups',
                'limit',
                'orderby',
                'page',
                'total',
                'count',
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
            'partno' => Yii::t('app', 'Part No.'),
            'partno_like' => Yii::t('app', 'Part No.'),
            'brand' => Yii::t('app', 'Manufacturer'),
            'model_brands' => Yii::t('app', 'Brand'),
            'serial_like' => Yii::t('app', 'Serial'),
            'move_type_label' => Yii::t('app', 'Move type'),
            'move_time' => Yii::t('app', 'Time'),
            'order_data' => Yii::t('app', 'Order'),
            'order_data_like' => Yii::t('app', 'Order'),
            'model_types' => Yii::t('app', 'Type'),
            'move_descr_like' => Yii::t('app', 'Move description'),
            'order_no' => Yii::t('app', 'Order'),
            'src_id' => Yii::t('app', 'Source'),
            'dst_id' => Yii::t('app', 'Destination'),
            'move_type' => Yii::t('app', 'Type'),
            'src_name_like' => Yii::t('app', 'Source'),
            'dst_name_like' => Yii::t('app', 'Destination'),
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
     * Group all results by dst_id
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
