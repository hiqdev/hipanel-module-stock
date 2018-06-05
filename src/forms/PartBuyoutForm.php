<?php

namespace hipanel\modules\stock\forms;

use hipanel\modules\stock\models\Part;
use Yii;

class PartBuyoutForm extends Part
{
    public function attributes()
    {
        return array_merge(parent::attributes(), ['contact_id', 'buyout_datetime', 'parts', 'client_id']);
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['contact_id', 'buyout_datetime', 'currency'], 'required'],
            [['client_id', 'contact_id'], 'integer'],
            [['parts'], 'each', 'rule' => ['required']],
            [['parts'], 'each', 'rule' => ['number']],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'buyout_datetime' => Yii::t('hipanel:stock', 'Sell datetime'),
            'contact_id' => Yii::t('hipanel', 'Contact'),
        ]);
    }
}

