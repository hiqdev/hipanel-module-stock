<?php

declare(strict_types=1);

namespace hipanel\modules\stock\forms;

use hipanel\base\Model;
use Yii;

final class FastMoveForm extends Model
{
    public function rules()
    {
        return [
            [['dst', 'partno'], 'string'],
            [['src_id', 'quantity'], 'integer'],
            [['src_id', 'quantity', 'partno', 'dst'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'partno' => Yii::t('hipanel:stock', 'Part No.'),
            'src_id' => Yii::t('hipanel:stock', 'Source'),
            'quantity' => Yii::t('hipanel', 'Quantity'),
            'dst' => Yii::t('hipanel:stock', 'Destination'),
        ];
    }
}
