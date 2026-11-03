<?php

namespace hipanel\modules\stock\forms;

use hipanel\modules\stock\models\Part;
use Yii;

class PartSellAsInstallmentForm extends Part
{
    public static function tableName()
    {
        return 'part';
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'ids', 'client_id', 'currency', 'monthly_sum', 'since', 'months', 'reason',
        ]);
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['client_id', 'currency', 'monthly_sum', 'since', 'months'], 'required'],
            [['client_id'], 'integer'],
            [['currency'], 'string'],
            [['monthly_sum'], 'number', 'min' => 0],
            [['since'], 'date', 'format' => 'php:Y-m-d'],
            [['months'], 'integer', 'min' => 1],
            [['reason'], 'string'],
            [['ids'], 'each', 'rule' => ['integer']],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'client_id'   => Yii::t('hipanel', 'Client'),
            'currency'    => Yii::t('hipanel', 'Currency'),
            'monthly_sum' => Yii::t('hipanel:stock', 'Monthly sum'),
            'since'       => Yii::t('hipanel:stock', 'Installment start'),
            'months'      => Yii::t('hipanel:stock', 'Number of months'),
            'reason'      => Yii::t('hipanel', 'Reason'),
        ]);
    }

    public function scenarioActions(): array
    {
        return [
            'sell-as-installment' => 'sell-as-installment',
        ];
    }
}
