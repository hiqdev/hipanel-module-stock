<?php

namespace hipanel\modules\stock\models;

use hipanel\base\Model;
use hipanel\base\ModelTrait;
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
                'id',
                'partno',
                'model_id',
                'serials',
                'src_id',
                'dst_id',
                'move_type',
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
            // Move
            [[
                'id',
                'src_id',
                'dst_id',
                'type',
                'descr',
                'remotehands',
                'remote_ticket',
                'hm_ticket',
            ], 'safe', 'on' => ['move']],
            // Reserve
            [[
                'id',
                'reserve',
                'descr',
            ], 'safe', 'on' => ['reserve']],
            // Un-Reserve
            [[
                'id',
                'reserve',
                'descr',
            ], 'safe', 'on' => ['un-reserve']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'id' => Yii::t('app', 'ID'),
            'partno' => Yii::t('app', 'Part No.'),
            'partno_like' => Yii::t('app', 'Part No.'),
            'type' => Yii::t('app', 'Type'),
            'brand' => Yii::t('app', 'Manufacturer'),
            'model_brands' => Yii::t('app', 'Brand'),
            'serial' => Yii::t('app', 'Serial'),
            'serial_like' => Yii::t('app', 'Serial'),
            'last_move' => Yii::t('app', 'Last move'),
            'move_type_label' => Yii::t('app', 'Move type'),
            'move_time' => Yii::t('app', 'Time'),
            'order_data' => Yii::t('app', 'Order'),
            'order_data_like' => Yii::t('app', 'Order'),
            'model_types' => Yii::t('app', 'Type'),
            'move_descr_like' => Yii::t('app', 'Move description'),
            'descr' => Yii::t('app', 'Description'),
            'order_no' => Yii::t('app', 'Order'),
            'src_id' => Yii::t('app', 'Source'),
            'dst_id' => Yii::t('app', 'Destination'),
            'move_type' => Yii::t('app', 'Type'),
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
}