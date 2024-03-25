<?php

namespace hipanel\modules\stock\forms;

use hipanel\modules\stock\models\Part;
use Yii;

class PartSellByPlanForm extends Part
{
    public static function tableName()
    {
        return 'part';
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['contact_id', 'client_id', 'time', 'description', 'ids', 'plan_id', 'reason']);
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['contact_id', 'time', 'plan_id'], 'required'],
            [['plan_id'], 'integer'],
            [['client_id', 'contact_id'], 'integer'],
            [['description'], 'string'],
            [['ticket_id'], 'integer'],
            [['ids'], 'each', 'rule' => ['integer']],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'time' => Yii::t('hipanel:stock', 'Sell datetime'),
            'plan_id' => Yii::t('hipanel:stock', 'Tariff plan'),
            'contact_id' => Yii::t('hipanel', 'Contact'),
            'description' => Yii::t('hipanel', 'Description'),
            'reason' => Yii::t('hipanel', 'Reason'),
        ]);
    }

    public function scenarioActions(): array
    {
        return [
            'sell-by-plan' => 'sell-by-plan',
        ];
    }
}

