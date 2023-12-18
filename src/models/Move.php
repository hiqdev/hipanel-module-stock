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
use hipanel\modules\stock\models\query\MoveQuery;
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
                    'replaced_part',
                    'replace_part',
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
        if ($replacedPart = $this->getReplacedPart()) {
            $replacedPartLink = Html::a(
                Html::encode($replacedPart->serial),
                ['@part/view', 'id' => $replacedPart->id],
                ['class' => 'text-bold']
            );
            $replace = Yii::t('hipanel:stock', '{type}: {part}', [
                'type' => Yii::t('hipanel:stock', $this->type_label),
                'part' => $replacedPartLink,
            ]);
        }

        return implode('<br>', [static::prepareDescr($this->descr), $replace ?? '']);
    }

    public static function prepareDescr(?string $descr): array|string|null
    {
        if (empty($descr)) {
            return null;
        }
        $urlPattern = '/(http|https)\:\/\/?[a-zA-Z0-9\.\/\?\:@\-_=#]+[a-zA-Z0-9\&\.\/\?\:@\-_=#]*/';
        if (str_contains($descr, '//hm4')) {
            return preg_replace_callback('@https://\S+/(\d+)/?(#\S+)?@', static function ($m) {
                return Html::a('HM4::' . Html::encode($m[1]), Html::encode($m[0]));
            }, $descr);
        } else if (str_contains($descr, 'http') && preg_match('/([A-Z]+\-\d+)/', $descr)) {
            return preg_replace_callback($urlPattern, function ($url) {
                preg_match('/([A-Z]+\-\d+)/', $url[0], $matches);
                return Html::a($matches[1], $url[0]);
            }, $descr);
        } else if (str_contains($descr, 'http')) {
            return preg_replace_callback($urlPattern, function ($url) {
                return Html::a($url[0], $url[0]);
            }, $descr);

        }

        return $descr;
    }

    public function isTrashed(): bool
    {
        return !empty($this->dst_name) && in_array(mb_strtolower($this->dst_name), ['trash', 'trash_rma'], true);
    }

    public function getReplacedPart(): ?Part
    {
        foreach (['replace_part', 'replaced_part'] as $try) {
            if (!empty($this->{$try})) {
                $attribute = $try;
            }
        }
        if (empty($attribute)) {
            return null;
        }
        $part = new Part();
        $part->setAttributes($this->{$attribute}, false);

        return $part;
    }

    public static function find(): MoveQuery
    {
        return new MoveQuery(static::class);
    }
}
