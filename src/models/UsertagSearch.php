<?php
namespace hipanel\modules\stock\models;

use hipanel\base\SearchModelTrait;
use hipanel\helpers\ArrayHelper;

class UsertagSearch extends Usertag
{
    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }

    public function searchAttributes()
    {
        return ArrayHelper::merge($this->defaultSearchAttributes(), []);
    }
}