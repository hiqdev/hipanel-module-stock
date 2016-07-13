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
use Yii;
use yii\helpers\Html;

class Move extends \hipanel\base\Model
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
                'part_ids',
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
                'name',
            ], 'safe'],
            [['id'], 'required', 'on' => 'delete'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'id'        => Yii::t('app', 'ID'),
            'partno'    => Yii::t('app', 'Part No.'),
            'src_id'    => Yii::t('app', 'Source'),
            'dst_id'    => Yii::t('app', 'Destination'),
            'src_name'  => Yii::t('app', 'Source'),
            'dst_name'  => Yii::t('app', 'Destination'),
            'serial'    => Yii::t('app', 'Serial'),
            'descr'     => Yii::t('app', 'Move description'),
        ]);
    }

    public function getDescription()
    {
        return static::prepareDescr($this->descr);
    }

    public static function prepareDescr($descr)
    {
        return preg_replace_callback('#https://\S+/(\d+)/?#', function ($m) {
            return Html::a('HM4::' . $m[1], $m[0]);
        }, $descr);
    }
}
