<?php


namespace hipanel\modules\stock\grid;


use hipanel\grid\RefColumn;

/**
 * Class CompanyColumn
 * @package hipanel\modules\stock\grid
 */
class CompanyColumn extends RefColumn
{
    /**
     * @inheritdoc
     */
    public $filterOptions = ['class' => 'narrow-filter'];

    /**
     * @inheritdoc
     */
    public $gtype = 'type,part_company';

    /**
     * @inheritdoc
     */
    public $findOptions = [
        'select' => 'id_label',
        'mapOptions' => [
            'from' => 'id',
        ],
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->label = \Yii::t('hipanel:stock', 'Company');
        $this->value = function ($model) {
            return $model->company;
        };
    }
}
