<?php
namespace hipanel\modules\stock\models;

use hipanel\base\Model;
use hipanel\base\ModelTrait;
use Yii;

class Profile extends Model
{
    use ModelTrait;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // Search
            [[
                'id',
                'name',
                'class',
            ], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
        ];
    }
}