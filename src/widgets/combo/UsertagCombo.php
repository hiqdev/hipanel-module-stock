<?php

namespace hipanel\modules\stock\widgets\combo;

use hiqdev\combo\Combo;
use yii\helpers\ArrayHelper;

class UsertagCombo extends Combo
{
    public $profileType = '';

    /** {@inheritdoc} */
    public $type = 'stock/usertag';

    /** {@inheritdoc} */
    public $name = 'name';

    /** {@inheritdoc} */
    public $url = '/stock/usertag/index';

    /** {@inheritdoc} */
    public $_return = ['id'];

//    /** @inheritdoc */
//    public function getFilter()
//    {
//        return ArrayHelper::merge(parent::getFilter(), [
//            'type'   => ['format' => sprintf('type,model,%s', $this->profileType)],
//        ]);
//    }
}