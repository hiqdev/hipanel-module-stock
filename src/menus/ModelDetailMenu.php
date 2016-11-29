<?php

namespace hipanel\modules\stock\menus;

use hiqdev\menumanager\Menu;
use Yii;

class ModelDetailMenu extends Menu
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
