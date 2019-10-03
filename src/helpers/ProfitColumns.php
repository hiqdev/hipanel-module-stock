<?php


namespace hipanel\modules\stock\helpers;

use Yii;

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
        foreach (['total', 'uu', 'stock', 'rma'] as $attr) {
            foreach (['usd', 'eur'] as $cur) {
                $columns[] = "{$attr}_price.{$cur}";
            }
        }
        foreach (['rent', 'leasing', 'buyout'] as $attr) {
            foreach (['usd', 'eur'] as $cur) {
                foreach (['price', 'charge'] as $type) {
                    $columns[] = "{$attr}_{$type}.{$cur}";
                }
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
            [$attr, $cur] = explode('.', $profitColumn);
            $columns[$profitColumn] = $pack($attr, $cur);
        }
        return $columns;
    }

    public static function getLabels()
    {
        $labels = [];
        foreach ([
            'total'     => 'TOTAL',
            'uu'        => 'Unused',
            'stock'     => 'Stock',
            'rma'       => 'RMA',
            'rent'      => 'Rent',
            'leasing'   => 'Leasing',
            'buyout'    => 'Buyout',
        ] as $name => $label) {
            foreach (['price', 'charge'] as $type) {
                foreach (['usd', 'eur'] as $cur) {
                    $labels["${name}_$type.$cur"] = Yii::t('hipanel.stock.order', $label.' '.ucfirst($type).' '.strtoupper($cur));
                }
            }
        }

        return $labels;
    }
}
