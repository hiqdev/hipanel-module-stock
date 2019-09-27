<?php

namespace hipanel\modules\stock\grid;

use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

class OrderRepresentations extends RepresentationCollection
{
    protected function fillRepresentations()
    {
        $this->representations = array_filter([
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => [
                    'actions', 'type', 'state',
                    'seller', 'buyer', 'parts',
                    'comment', 'time',
                ],
            ],
            'profit-report' => [
                'label' => Yii::t('hipanel', 'profit report'),
                'columns' => $this->getProfitReportColumns(),
            ],
        ]);
    }

    private function getProfitReportColumns(): array
    {
        $attrs = ['comment', 'time'];
        foreach (['total', 'uu', 'stock', 'rma', 'rent', 'leasing', 'buyout'] as $attr) {
            foreach (['eur', 'usd'] as $cur) {
                $attrs[] = "{$attr}_{$cur}";
            }
        }
        return $attrs;
    }
}
