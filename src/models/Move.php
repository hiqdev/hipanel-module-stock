<?php

namespace hipanel\modules\stock\models;

use hipanel\base\Model;
use hipanel\base\ModelTrait;
use Yii;

class Move extends Model
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
                'client_id',
                'parts',
                'client',
                'src_name',
                'dst_name',
                'type_label',
                'state_label',
                'descr',
                'time',
                'data',
                'remotehands_id',
                'remote_ticket',
                'hm_ticket',
                'type_label',

                'type',
                'types',
                'state',
                'states',
                'src_id',
                'dst_id',
                'partno',
                'partno_like',
                'src_name_like',
                'dst_name_like',
                'serial_like',
                'data',
                'data_like',
                'descr_like',
                'with_parts',
            ], 'safe'],
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
            'descr' => Yii::t('app', 'Description'),
            'src_id' => Yii::t('app', 'Source'),
            'dst_id' => Yii::t('app', 'Destination'),
            'data' => Yii::t('app', 'Data'),
            'src_name_like' => Yii::t('app', 'Source'),
            'dst_name_like' => Yii::t('app', 'Destination'),
            'serial_like' => Yii::t('app', 'Serial'),
            'descr_like' => Yii::t('app', 'Move description'),

        ]);
    }
}