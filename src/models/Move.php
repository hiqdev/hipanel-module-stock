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
            'src_id' => Yii::t('app', 'Source'),
            'dst_id' => Yii::t('app', 'Destination'),
            'src_name_like' => Yii::t('app', 'Source'),
            'dst_name_like' => Yii::t('app', 'Destination'),
            'serial_like' => Yii::t('app', 'Serial'),
            'descr_like' => Yii::t('app', 'Move description'),
        ]);
    }
}
