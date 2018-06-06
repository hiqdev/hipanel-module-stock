<?php

namespace hipanel\modules\stock\tests\acceptance\client;

use hipanel\tests\_support\Page\SidebarMenu;
use hipanel\tests\_support\Step\Acceptance\Client;

class StockSidebarMenuCest
{
    public function ensureMenuIsOk(Client $I)
    {
        (new SidebarMenu($I))->ensureDoesNotContain('Stock');
    }
}
