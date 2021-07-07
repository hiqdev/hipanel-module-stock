<?php

declare(strict_types=1);

namespace hipanel\modules\stock\forms;

use hipanel\base\Model;
use hipanel\helpers\ArrayHelper;
use hipanel\modules\server\models\Server;
use Yii;

final class FastMoveForm extends Model
{
    public function rules()
    {
        return [
            [['dst', 'destinations', 'partno'], 'string'],
            [['src_id', 'quantity'], 'integer'],
            [['src_id', 'quantity', 'partno', 'destinations'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'partno' => Yii::t('hipanel:stock', 'Part No.'),
            'src_id' => Yii::t('hipanel:stock', 'Source'),
            'quantity' => Yii::t('hipanel', 'Quantity'),
            'destinations' => Yii::t('hipanel:stock', 'Servers'),
        ];
    }

    public function multiplyByDestinations(): array
    {
        $models = [];
        $destinations = ArrayHelper::htmlEncode(preg_split('/[\s,;]+/', trim($this->destinations)));
        $servers = $this->resolveDestinations($destinations);
        foreach ($servers as $server) {
            $model = clone $this;
            $model->dst = $server->dc;
            $models[] = $model;
        }

        return $models;
    }

    private function resolveDestinations(array $destinations): array
    {
        return Server::find()->where(['dcs' => $destinations, 'primary_only' => true])->limit(-1)->all();
    }
}
