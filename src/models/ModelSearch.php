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

use hipanel\base\SearchModelTrait;
use hipanel\helpers\ArrayHelper;
use Yii;

class ModelSearch extends Model
{
    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }

    public function searchAttributes()
    {
        return ArrayHelper::merge($this->defaultSearchAttributes(), [
            'group_like', 'filter_like', 'hide_unavailable', 'hide_group_assigned', 'partno_inilike',
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'partno_like' => Yii::t('hipanel:stock', 'Part No.'),
            'partno_inilike' => Yii::t('hipanel:stock', 'Part No.'),
            'filter_like' => Yii::t('hipanel:stock', 'Filter'),
            'descr_like' => Yii::t('hipanel:stock', 'Description'),
            'short_like' => Yii::t('hipanel:stock', 'Short'),
            'group_like' => Yii::t('hipanel:stock', 'Group'),
            'model_like' => Yii::t('hipanel:stock', 'Model'),
            'brand_like' => Yii::t('hipanel:stock', 'Brand'),
            'hide_unavailable' => Yii::t('hipanel:stock', 'Hide unavailable'),
            'hide_group_assigned' => Yii::t('hipanel:stock', 'Hide assigned to group'),
        ]);
    }
}
