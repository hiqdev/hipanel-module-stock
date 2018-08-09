<?php

namespace hipanel\modules\stock\forms;

use hipanel\modules\stock\models\Part;
use Yii;

class PartSellForm extends Part
{
    public function attributes()
    {
        return array_merge(parent::attributes(), ['contact_id', 'time', 'sums', 'client_id', 'description', 'ids']);
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['contact_id', 'time', 'currency'], 'required'],
            [['client_id', 'contact_id'], 'integer'],
            [['description'], 'string'],
            [['ids'], 'each', 'rule' => ['integer']],
            [['sums'], 'each', 'rule' => ['required']],
            [['sums'], 'each', 'rule' => ['number']],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'time' => Yii::t('hipanel:stock', 'Sell datetime'),
            'contact_id' => Yii::t('hipanel', 'Contact'),
            'description' => Yii::t('hipanel', 'Description'),
        ]);
    }

    public function calculateSums(): string
    {
        $result = 0;
        if (!empty($this->sums)) {
            foreach ($this->sums as $sum) {
                $result += is_numeric($sum) ? $sum : 0;
            }
        }

        return Yii::$app->formatter->asCurrency($result, $this->currency);
    }
}

