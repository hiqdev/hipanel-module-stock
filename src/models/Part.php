<?php

namespace hipanel\modules\stock\models;

use hipanel\base\Model;
use hipanel\base\ModelTrait;

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
            // Update
            [[
                'serial',
                'price',
            ], 'safe', 'on' => ['update']],
            // Move
            [[
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
                'reserve',
                'descr',
            ], 'safe', 'on' => ['reserve']],
            // Un-Reserve
            [[
                'reserve',
                'descr',
            ], 'safe', 'on' => ['un-reserve']],
        ];
    }
}