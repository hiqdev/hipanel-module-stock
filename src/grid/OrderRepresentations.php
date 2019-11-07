<?php

namespace hipanel\modules\stock\grid;

use hipanel\modules\stock\helpers\ProfitColumns;
use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

/**
 * Class OrderRepresentations
 * @package hipanel\modules\stock\grid
 */
class OrderRepresentations extends RepresentationCollection
{
    /**
     * @inheritDoc
     */
    public function fillRepresentations()
    {
        $this->representations = array_filter([
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => [
                    'actions', 'type', 'state',
                    'seller', 'buyer',
                    'name', 'company_id', 'parts', 'time',
                ],
            ],
//            'profit-report' => Yii::$app->user->can('order.read-profits') ? [
//                'label' => Yii::t('hipanel', 'profit report'),
//                'columns' => ProfitColumns::getColumns(['name_profit', 'company_id', 'parts', 'time']),
//            ] : null,
            'profit-report' => [
                'label' => Yii::t('hipanel', 'profit report'),
                'columns' => ProfitColumns::getColumns(['name_profit', 'company_id', 'parts', 'time']),
            ],
        ]);
    }
}
