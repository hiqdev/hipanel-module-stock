<?php
namespace hipanel\modules\stock\models;

use hipanel\base\Model;
use hipanel\base\ModelTrait;
use Yii;

class Usertag extends Model
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
                'name_like',
                'type',
            ], 'safe', 'on' => 'search'],
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