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
    use SearchModelTrait
    {
        searchAttributes as defaultSearchAttributes;
    }

    public function searchAttributes()
    {
        return ArrayHelper::merge($this->defaultSearchAttributes(), [
            'group_like'
        ]);
    }
    
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'partno_like' => Yii::t('hipanel/stock', 'Part No.'),
            'descr_like' => Yii::t('hipanel/stock', 'Description'),
            'short_like' => Yii::t('hipanel/stock', 'Short'),
            'group_like' => Yii::t('hipanel/stock', 'Group'),
            'model_like' => Yii::t('hipanel/stock', 'Model'),
            'brand_like' => Yii::t('hipanel/stock', 'Brand'),
        ]);
    }
}
