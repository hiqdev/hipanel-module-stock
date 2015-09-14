<?php

namespace hipanel\modules\stock\models;

use hipanel\base\Model as YiiModel;
use hipanel\base\ModelTrait;
use Yii;

class Model extends YiiModel
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
                'state_label',
                'brand_label',
                'url',
                'descr',
                'short',
                'is_favourite',
                'profile',
                'profile_id',
                'last_prices',
                'model',
                'model_like',
                'type',
                'types',
                'state',
                'states',
                'brand',
                'brands',
                'tag',
                'tags',
                'tags_all',
                'prop_tag',
                'prop_tags',
                'prop_tags_all',
                'partno',
                'url_like',
                'partno_like',
                'descr_like',
                'short_like',
                'brand_like',
                'group_like',
                'with_counters',
                'with_prices',
                'tariff_id',
                'show_system',
                'show_hidden_from_user',
            ], 'safe'],
            // Create & Update
            [[
                'id',
                'type',
                'partno',
                'brand',
                'model',
                'url',
                'descr',
                'short',
                'is_favourite',
                'profile',
                'tags',
                'prop_tags',
                'props',
            ], 'safe', 'on' => ['create', 'update']],
            // Hide & Show
            ['id', 'required', 'on' => ['mark-hidden-from-user', 'un-mark-hidden-from-user']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'id' => Yii::t('app', 'ID'),
            'last_prices' => Yii::t('app', 'Last price'),
        ]);
    }
}