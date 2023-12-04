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
            'model_types', 'model_type', 'model_brands',
            'model_type_like', 'model_brand_like',
            'partno_like', 'serial_ilike',
            'first_move_ilike', 'order_data_like',
            'src_name_like', 'dst_name_like', 'move_descr_like', 'move_descr_ilike',
            'create_time_from', 'create_time_till', 'id_in', 'buyer_in', 'partno_inilike', 'partno_in',
            'profit_time_from', 'profit_time_till',
            'company', 'model_brand',
            'order_name_ilike',
            'device_location_like',
            'move_type_and_date',
            'rack', 'rack_in', 'rack_ilike',
            'last_move_ilike',
            'partno_leftlikei',
            'stock_location_in',
            'stock_location_state',
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'place_in'          => Yii::t('hipanel:stock', 'Location'),
            'partno_like'       => Yii::t('hipanel:stock', 'Part No.'),
            'partno_inilike'    => Yii::t('hipanel:stock', 'Part No.'),
            'serial_ilike'      => Yii::t('hipanel:stock', 'Serial'),
            'order_data_like'   => Yii::t('hipanel:stock', 'Order'),
            'move_descr_like'   => Yii::t('hipanel:stock', 'Move description'),
            'move_descr_ilike'  => Yii::t('hipanel:stock', 'Move description'),
            'src_name_like'     => Yii::t('hipanel:stock', 'Source'),
            'dst_name_like'     => Yii::t('hipanel:stock', 'Destination'),
            'id_in'             => Yii::t('hipanel:stock', 'Parts'),
            'buyer_in'          => Yii::t('hipanel:stock', 'Buyers'),
            'order_name_ilike'  => Yii::t('hipanel:stock', 'Order'),
            'device_location_like' => Yii::t('hipanel:stock', 'DC location'),
            'rack'              => Yii::t('hipanel:server', 'Rack'),
            'rack_in'           => Yii::t('hipanel:server', 'Rack'),
            'first_move_ilike'  => Yii::t('hipanel:stock', 'First move'),
            'last_move_ilike'   => Yii::t('hipanel:stock', 'Last move'),
            'stock_location_in' => Yii::t('hipanel:stock', 'Stock location'),
        ]);
    }
}
