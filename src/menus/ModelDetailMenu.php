<?php

namespace hipanel\modules\stock\menus;

use Yii;

class ModelDetailMenu extends \hipanel\menus\AbstractDetailMenu
{
    public $model;

    public function items()
    {
        return [
            [
                'label' => Yii::t('hipanel:stock', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@model/update', 'id' => $this->model->id],
            ],
        ];
    }
}
