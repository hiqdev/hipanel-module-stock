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
                    'replaced_part'
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
            'first_move_ilike' => Yii::t('hipanel:stock', 'First move'),
        ]);
    }

    public function getDescription(): array|string|null
    {
        $replacedPartLink = null;
        if ($replacedPart = $this->getReplacedPart()) {
            $replacedPartLink = Html::a(
                Html::encode($replacedPart->serial),
                ['@part/view', 'id' => $replacedPart->id],
                ['class' => 'text-bold']
            );
        }

        return implode('<br>', [static::prepareDescr($this->descr), $replacedPartLink]);
    }

    public static function prepareDescr(?string $descr): array|string|null
    {
        if (empty($descr)) {
            return null;
        }

        return preg_replace_callback('@https://\S+/(\d+)/?(#\S+)?@', static function ($m) {
            return Html::a('HM4::' . Html::encode($m[1]), Html::encode($m[0]));
        }, $descr);
    }

    public function isTrashed(): bool
    {
        return !empty($this->dst_name) && in_array(mb_strtolower($this->dst_name), ['trash', 'trash_rma'], true);
    }

    public function getReplacedPart(): ?Part
    {
        if (empty($this->replaced_part)) {
            return null;
        }
        $part = new Part();
        $part->setAttributes($this->replaced_part, false);

        return $part;
    }
}
