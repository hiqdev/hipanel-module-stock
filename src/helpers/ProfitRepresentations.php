<?php


namespace hipanel\modules\stock\helpers;


class ProfitRepresentations
{
    public static function getColumns(\Closure $pack, array $commonColumns = []): array
    {
        foreach (['total', 'uu', 'stock', 'rma', 'rent', 'leasing', 'buyout'] as $attr) {
            foreach (['usd', 'eur'] as $cur) {
                $res = $pack($attr, $cur);
                if (empty($res['key'])) {
                    $attrs[] = $res['value'];
                } else {
                    $attrs[$res['key']] = $res['value'];
                }
            }
        }
        return array_merge($commonColumns, $attrs);
    }
}
