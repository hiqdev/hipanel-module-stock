<?php declare(strict_types=1);

namespace hipanel\modules\stock\enums;

final class StockLocationType
{
    public const string ALIAS = 'alias';
    public const string BUILDING = 'building';
    public const string CAGE = 'cage';
    public const string CHWBOX = 'chwbox';
    public const string CHWBOX_GROUP = 'chwbox_group';
    public const string DC = 'dc';
    public const string FOR_TEST = 'for-test';
    public const string LOCATION = 'location';
    public const string RACK = 'rack';
    public const string RMA = 'rma';
    public const string STOCK = 'stock';
    public const string STOCK_GROUP = 'stock_group';
    public const string USED = 'used';
    public const string DELETED = 'deleted';
    public const string SOLD = 'sold';
    public const string SUPPLIER = 'supplier';
    public const string TRASH = 'trash';
    public const string ALIAS_GROUP_USED = 'alias_group_used';
    public const string ALIAS_GROUP_RMA = 'alias_group_rma';
    public const string ALIAS_GROUP_FOR_TEST = 'alias_group_for-test';
    public const string ALIAS_GROUP_STOCK = 'alias_group_stock';
    public const string DISPOSAL = 'disposal';

    private(set) string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function from(string $value): self
    {
        return new self($value);
    }
}
