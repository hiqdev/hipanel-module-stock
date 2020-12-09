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

use hipanel\helpers\Url;
use hipanel\modules\client\ClientWithCounters;
use hiqdev\yii2\menus\Menu;
use Yii;

class DashboardItem extends Menu
{
    protected ClientWithCounters $clientWithCounters;

    public function __construct(ClientWithCounters $clientWithCounters, $config = [])
    {
        $this->clientWithCounters = $clientWithCounters;
        parent::__construct($config);
    }

    public function items()
    {
        $items = [];
        if (Yii::$app->user->can('part.read')) {
            $items['part'] = [
                'label' => $this->render('dashboardPartItem', array_merge($this->clientWithCounters->getWidgetData('part'), [
                    'route' => Url::toRoute('@part/index'),
                ])),
                'encode' => false,
            ];
        }
        if (Yii::$app->user->can('model.read')) {
            $items['model'] = [
                'label' => $this->render('dashboardModelItem', array_merge($this->clientWithCounters->getWidgetData('model'), [
                    'route' => Url::toRoute('@model/index'),
                ])),
                'encode' => false,
            ];
        }

        return $items;
    }
}
