<?php


namespace hipanel\modules\stock\helpers;

use hipanel\base\Model;
use hipanel\grid\BoxedGridView;
use hipanel\modules\stock\models\Order;
use hipanel\modules\stock\models\OrderWithProfit;
use hipanel\modules\stock\models\Part;
use hipanel\modules\stock\models\PartWithProfit;
use Yii;
use yii\helpers\Html;

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
        foreach (['total', 'unused', 'stock', 'rma'] as $attr) {
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

    /**
     * @return array
     */
    public static function getLabels()
    {
        $labels = [];
        foreach ([
            'total'     => 'TOTAL',
            'unused'    => 'Unused',
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

    /**
     * @param PartWithProfit[]|OrderWithProfit[] $profits
     * @param string $attr
     * @param string $cur
     * @return PartWithProfit|OrderWithProfit|null
     */
    private static function getRedusedProfitByCurrency(array $profits, string $attr, string $cur): ?Model
    {
        return array_reduce($profits, function ($result, $profit) use ($attr, $cur) {
            if ($profit->currency === $cur && !empty($profit->{$attr})) {
                return $profit;
            }
            return $result;
        }, null);
    }

    /**
     * @param BoxedGridView $gridView
     * @param string $linkAttribute
     * @return array
     */
    public static function getProfitColumns(BoxedGridView $gridView, string $linkAttribute): array
    {
        return ProfitColumns::getGridColumns(function (string $attr, string $cur) use ($gridView, $linkAttribute): array {
            $valueArray = [
                'value' => function (Model $model) use ($attr, $cur, $linkAttribute): string {
                    /** @var Part|Order $model */
                    $profit = static::getRedusedProfitByCurrency($model->profit, $attr, $cur);
                    if ($profit === null) {
                        return '';
                    }
                    $result = (string)number_format($profit->{$attr}, 2);
                    if (empty(strpos($attr, 'charge'))) {
                        return $result;
                    }
                    return HTML::a($result, ['/finance/charge/index', $linkAttribute => $model->id]);
                },
                'format' => 'raw',
                'contentOptions' => ['class' => 'text-right'],
                'footerOptions' => ['class' => 'text-right'],
            ];
            if ($gridView->showFooter) {
                $valueArray['footer'] = static::calculateFooter($attr, $cur, $gridView);
            }
            return $valueArray;
        });
    }

    /**
     * @param string $attr
     * @param string $cur
     * @param BoxedGridView $gridView
     * @return string
     */
    private static function calculateFooter(string $attr, string $cur, BoxedGridView $gridView): string
    {
        $models = $gridView->dataProvider->getModels();
        $sum = array_reduce($models, function (float $sum, Model $model) use ($attr, $cur): float {
            $profit = static::getRedusedProfitByCurrency($model->profit, $attr, $cur);

            if ($profit && $profit->currency === $cur) {
                return $sum + $profit->{$attr};
            }
            return $sum;
        }, 0.0);
        return empty($sum) ? '' : number_format($sum, 2);
    }
}
