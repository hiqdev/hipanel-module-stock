<?php declare(strict_types=1);

namespace hipanel\modules\stock\enums;

final class StockLocationCategory
{
    public const string STOCK = 'stock';
    public const string STOCK_GROUP = 'stock_group';
    public const string CHWBOX = 'chwbox';
    public const string CHWBOX_GROUP = 'chwbox_group';
    public const string LOCATION = 'location';
    public const string LOCATION_GROUP = 'location_group';
    public const string ALIAS_GROUP = 'alias_group';
    public const string ALIAS_GROUP_BY_STOCK_STATE = 'alias_group_by_stock_state';

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
