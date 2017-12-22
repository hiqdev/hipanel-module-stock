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
            [['id', 'src_id', 'dst_id', 'client_id', 'remotehands_id'], 'integer'],
            [
                [
                    'part_ids',
                    'parts',
                    'client',
                    'src_name',
                    'dst_name',
                    'type_label',
                    'state_label',
                    'descr',
                    'time',
                    'data',
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
                ],
                'safe',
            ],

            // Delete
            [['id'], 'required', 'on' => 'delete'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'parts' => Yii::t('hipanel:stock', 'Parts'),
            'partno' => Yii::t('hipanel:stock', 'Part No.'),
            'src_id' => Yii::t('hipanel:stock', 'Source'),
            'dst_id' => Yii::t('hipanel:stock', 'Destination'),
            'src_name' => Yii::t('hipanel:stock', 'Source'),
            'dst_name' => Yii::t('hipanel:stock', 'Destination'),
            'serial' => Yii::t('hipanel:stock', 'Serial'),
            'descr' => Yii::t('hipanel:stock', 'Move description'),
            'data' => Yii::t('hipanel:stock', 'Data'),
        ]);
    }

    public function getDescription()
    {
        return static::prepareDescr($this->descr);
    }

    public static function prepareDescr($descr)
    {
        return preg_replace_callback('@https://\S+/(\d+)/?(#\S+)?@', function ($m) {
            return Html::a('HM4::' . $m[1], $m[0]);
        }, $descr);
    }
}
