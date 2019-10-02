<?php


namespace hipanel\modules\stock\helpers;

/**
 * Class ProfitColumns
 * @package hipanel\modules\stock\helpers
 */
final class ProfitColumns
{
    /**
     * @param string[] $commonColumns
     * @return string[]
     */
    public static function getColumns(array $commonColumns = []): array
    {
        foreach (['total', 'uu', 'stock', 'rma', 'rent', 'leasing', 'buyout'] as $attr) {
            foreach (['usd', 'eur'] as $cur) {
                $columns[] = "{$attr}_{$cur}";
            }
        }
        return array_merge($commonColumns, $columns);
    }

    /**
     * @param \Closure $pack
     * @return mixed[]
     */
    public static function getGridColumns(\Closure $pack): array
    {
        $profitColumns = static::getColumns();
        foreach ($profitColumns as $profitColumn) {
            [$attr, $cur] = explode('_', $profitColumn);
            $columns[$profitColumn] = $pack($attr, $cur);
        }
        return $columns;
    }
}
