<?php

namespace hipanel\modules\stock\tests\acceptance\admin;

use hipanel\tests\_support\Page\SidebarMenu;
use hipanel\tests\_support\Step\Acceptance\Admin;

class StockSidebarMenuCest
{
    public function ensureMenuIsOk(Admin $I)
    {
        (new SidebarMenu($I))->ensureContains('Stock',[
            'Models' => '@model/index',
            'Parts' => '@part/index',
            'History' => '@move/index',
            'Model groups' => '@model-group/index',
        ]);
    }
}
