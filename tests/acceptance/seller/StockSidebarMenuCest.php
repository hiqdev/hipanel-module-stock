<?php

namespace hipanel\modules\stock\tests\acceptance\seller;

use hipanel\tests\_support\Page\SidebarMenu;
use hipanel\tests\_support\Step\Acceptance\Seller;

class StockSidebarMenuCest
{
    public function ensureMenuIsOk(Seller $I)
    {
        (new SidebarMenu($I))->ensureContains('Stock', [
            'Models' => '@model/index',
            'Parts' => '@part/index',
            'History' => '@move/index',
            'Model groups' => '@model-group/index',
        ]);
    }
}
