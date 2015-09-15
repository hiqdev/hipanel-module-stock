<?php
namespace hipanel\modules\stock\widgets\combo;

use hipanel\helpers\ArrayHelper;
use hiqdev\combo\Combo;

class ProfileCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'stock/profile';

    /** {@inheritdoc} */
    public $name = 'name';

    /** {@inheritdoc} */
    public $url = '/stock/profile/index';

    /** {@inheritdoc} */
    public $_return = ['id'];

    public $profileClass = '';

    /** @inheritdoc */
    public function getFilter()
    {
        return ArrayHelper::merge(parent::getFilter(), [
            'class'   => ['format' => $this->profileClass],
        ]);
    }
}