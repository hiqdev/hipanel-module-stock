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
}
