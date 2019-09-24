<?php
/**
 * hipanel.advancedhosters.com
 *
 * @link      http://hipanel.advancedhosters.com/
 * @package   hipanel.advancedhosters.com
 * @license   proprietary
 * @copyright Copyright (c) 2016-2019, AdvancedHosters (https://advancedhosters.com/)
 */

namespace hipanel\modules\stock\menus;

use hiqdev\yii2\menus\Menu;
use Yii;

class OrderActionsMenu extends Menu
{
    public $model;

    public function items(): array
    {
        return [
            'view' => [
                'label' => Yii::t('hipanel', 'View'),
                'icon' => 'fa-info',
                'url' => ['@order/view', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('order.read'),
            ],
            'update' => [
                'label' => Yii::t('hipanel', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@order/update', 'id' => $this->model->id],
                'visible' => Yii::$app->user->can('order.update'),
            ],
        ];
    }
}
