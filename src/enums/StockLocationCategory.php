<?php declare(strict_types=1);

namespace hipanel\modules\stock\enums;

enum StockLocationCategory: string
{
    case STOCK = 'stock';
    case STOCK_GROUP = 'stock_group';
    case CHWBOX = 'chwbox';
    case CHWBOX_GROUP = 'chwbox_group';
    case LOCATION = 'location';
    case LOCATION_GROUP = 'location_group';
    case ALIAS_GROUP = 'alias_group';
    case ALIAS_GROUP_BY_STOCK_STATE = 'alias_group_by_stock_state';
}
