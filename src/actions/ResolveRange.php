<?php

namespace hipanel\modules\stock\actions;

use hipanel\modules\server\models\Server;
use hipanel\modules\stock\models\Part;
use yii\base\Action;
use yii\web\Response;

class ResolveRange extends Action
{
    public function run(): Response
    {
        $result = [];
        $range = mb_strtoupper($this->controller->request->post('id'));
        if ($range) {
            $servers = Server::find()->where([
                'name_like' => $range,
                'types' => Part::getDestinationSubTypes(),
                'primary_only' => true,
            ])->limit(-1)->all();
            foreach ($servers as $server) {
                $result[] = ['id' => $server->id, 'text' => $server->name];
            }
        }

        return $this->controller->asJson($result);
    }
}
