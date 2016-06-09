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

class PartSearch extends Part
{
    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }

    public function searchAttributes()
    {
        return ArrayHelper::merge($this->defaultSearchAttributes(), [
            'model_types', 'model_brands',
            'model_type_like', 'model_brand_like',
            'partno_like', 'serial_like',
            'order_data_like', 'move_descr_like',
            'src_name_like', 'dst_name_like',
            'create_time_from', 'create_time_till',
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'partno_like'       => Yii::t('app', 'Part No.'),
            'serial_like'       => Yii::t('app', 'Serial'),
            'order_data_like'   => Yii::t('app', 'Order'),
            'move_descr_like'   => Yii::t('app', 'Move description'),
            'src_name_like'     => Yii::t('hipanel/stock', 'Source'),
            'dst_name_like'     => Yii::t('hipanel/stock', 'Destination'),
        ]);
    }
}
