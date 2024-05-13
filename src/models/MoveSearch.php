<?php

declare(strict_types=1);

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

class MoveSearch extends Move
{
    use SearchModelTrait {
        SearchModelTrait::searchAttributes as defaultSearchAttributes;
    }

    public function searchAttributes()
    {
        return ArrayHelper::merge($this->defaultSearchAttributes(), [
            'first_move_ilike',
            'types',
            'name_like', // -> server_like
            'name_inilike',
            'partno_inilike',
            'src_or_dst',
            'time_till',
            'time_from',
            'show_deleted',
        ]);
    }

    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'src_or_dst' => Yii::t('hipanel:stock', 'Source or Destination'),
        ]);
    }
}
