<?php
/**
 * Dashboard Plugin for HiPanel.
 *
 * @link      https://github.com/hiqdev/hipanel-module-dashboard
 * @package   hipanel-module-dashboard
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\stock\menus;

use Yii;
use yii\widgets\Menu;

class DashboardItem extends \hiqdev\yii2\menus\Menu
{
    public $widgetConfig = [
        'class' => Menu::class,
        'itemOptions' => [
            'tag' => false,
        ],
        'options' => [
            'tag' => false,
        ],
    ];

    public function items()
    {
        return [
            'part' => [
                'label' => $this->render('dashboardPartItem'),
                'encode' => false,
                'visible' => Yii::$app->user->can('part.read'),
            ],
            'model' => [
                'label' => $this->render('dashboardModelItem'),
                'encode' => false,
                'visible' => Yii::$app->user->can('model.read'),
            ],
        ];
    }
}
