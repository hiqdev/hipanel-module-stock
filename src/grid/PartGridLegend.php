<?php

namespace hipanel\modules\stock\grid;

use hipanel\base\Model;
use hipanel\helpers\StringHelper;
use hipanel\modules\stock\models\Part;
use hipanel\widgets\gridLegend\GridLegendInterface;
use Yii;

class PartGridLegend implements GridLegendInterface
{
    /**
     * @var Part
     */
    protected $model;

    /**
     * @param Model $model
     */
    public function __construct(Part $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritdoc
     */
    public function items()
    {
        return [
            [
                'label' => Yii::t('hipanel:stock', 'Inuse'),
            ],
            [
                'label' => Yii::t('hipanel:stock', 'Reserve'),
                'color' => '#337ab7',
                'apply' => boolval($this->model->reserve),
            ],
            [
                'label' => Yii::t('hipanel:stock', 'Stock'),
                'color' => '#dff0d8',
                'rule' => StringHelper::startsWith(mb_strtolower($this->model->dst_name), 'stock_') && empty($this->model->reserve),
            ],
            [
                'label' => Yii::t('hipanel:stock', 'RMA'),
                'color' => '#f2dede',
                'rule' => StringHelper::startsWith(mb_strtolower($this->model->dst_name), 'rma_'),
            ],
            [
                'label' => Yii::t('hipanel:stock', 'TRASH'),
                'color' => '#fcf8e3',
                'rule' => in_array(mb_strtolower($this->model->dst_name), ['trash', 'trash_rma']),
            ],
        ];
    }
}
