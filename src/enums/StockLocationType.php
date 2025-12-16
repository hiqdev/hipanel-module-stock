<?php declare(strict_types=1);

namespace hipanel\modules\stock\enums;

enum StockLocationType: string
{
    case ALIAS = 'alias';
    case BUILDING = 'building';
    case CAGE = 'cage';
    case CHWBOX = 'chwbox';
    case CHWBOX_GROUP = 'chwbox_group';
    case DC = 'dc';
    case FOR_TEST = 'for-test';
    case LOCATION = 'location';
    case RACK = 'rack';
    case RMA = 'rma';
    case STOCK = 'stock';
    case STOCK_GROUP = 'stock_group';
    case USED = 'used';
    case DELETED = 'deleted';
    case SOLD = 'sold';
    case SUPPLIER = 'supplier';
    case TRASH = 'trash';
    case ALIAS_GROUP_USED = 'alias_group_used';
    case ALIAS_GROUP_RMA = 'alias_group_rma';
    case ALIAS_GROUP_FOR_TEST = 'alias_group_for-test';
    case ALIAS_GROUP_STOCK = 'alias_group_stock';
}
