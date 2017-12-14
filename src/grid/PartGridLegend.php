<?php

namespace hipanel\modules\stock\grid;

use hipanel\base\Model;
use hipanel\helpers\StringHelper;
use hipanel\modules\stock\models\Part;
use hipanel\widgets\gridLegend\BaseGridLegend;
use hipanel\widgets\gridLegend\GridLegendInterface;
use Yii;

class PartGridLegend extends BaseGridLegend implements GridLegendInterface
{
    /**
     * @inheritdoc
     */
    public function items()
    {
        return [
            [
                'label' => ['hipanel:stock', 'Inuse'],
            ],
            'reserve' => [
                'label' => ['hipanel:stock', 'Reserve'],
                'color' => '#d9edf7',
                'rule' => isset($this->model->reserve) ? boolval($this->model->reserve) : false,
            ],
            [
                'label' => ['hipanel:stock', 'Stock'],
                'color' => '#dff0d8',
                'rule' => StringHelper::startsWith(mb_strtolower($this->model->dst_name), 'stock_') && empty($this->model->reserve),
            ],
            [
                'label' => ['hipanel:stock', 'RMA'],
                'color' => '#f2dede',
                'rule' => StringHelper::startsWith(mb_strtolower($this->model->dst_name), 'rma_'),
            ],
            [
                'label' => ['hipanel:stock', 'TRASH'],
                'color' => '#fcf8e3',
                'rule' => in_array(mb_strtolower($this->model->dst_name), ['trash', 'trash_rma']),
            ],
        ];
    }
}
