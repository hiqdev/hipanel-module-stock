<?php
declare(strict_types=1);

namespace hipanel\modules\stock\controllers;

use hipanel\filters\EasyAccessControl;
use hipanel\modules\server\models\Hub;
use hipanel\modules\stock\models\Model;
use hipanel\modules\stock\models\Move;
use hipanel\modules\stock\models\Order;
use hipanel\modules\stock\models\Part;
use hipanel\modules\stock\Module;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use yii\web\User;

class MobileController extends Controller
{
    public function __construct(
        $id,
        Module $module,
        private readonly User $user,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    '*' => 'move.read',
                ],
            ],
            [
                'class' => VerbFilter::class,
                'actions' => [
                    'create-session' => ['post'],
                ],
            ],
        ]);
    }

    public function actionIndex(): string
    {
        $this->layout = 'mobile-app-layout';

        return $this->render('index');
    }

    public function actionGetSessions(): Response
    {
        $sessions = [
            ['id' => 1, 'name' => 'Session name ' . mt_rand()],
            ['id' => 2, 'name' => 'Session name ' . mt_rand()],
            ['id' => 3, 'name' => 'Session name ' . mt_rand()],
            ['id' => 3, 'name' => 'Session name ' . mt_rand()],
        ];

        return $this->response($sessions);
    }

    public function actionCreateSession(): Response
    {
        return $this->response(['id' => mt_rand(), 'name' => 'Name: ' . mt_rand()]);
    }

    public function actionGetTasks(): Response
    {
        return $this->response([
            ['id' => 1, 'name' => 'RHA-103'],
            ['id' => 2, 'name' => 'RHA-104'],
            ['id' => 3, 'name' => 'RHA-105'],
            ['id' => 3, 'name' => '1236187 (HM4)'],
        ]);
    }

    public function actionGetLocations(): Response
    {
        $locations = Hub::find()->where(['name_like' => 'AM7', 'type' => 'location'])->limit(-1)->all();

        return $this->response([
            ['name' => 'NL:AMS:EQ:AM7'],
            ['name' => 'NL:AMS:EQ:AM11'],
            ['name' => 'NL:AMS:DR:AMS17'],
            ['name' => 'USA:ASH:EQ:DC10'],
        ]);
    }

    public function actionGetUser(): Response
    {
        $user = $this->user->identity;

        return $this->response([
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
        ]);
    }

    public function actionResolveCode($code, $location): Response
    {
        [$entityName, $result] = $this->resolve($code, $location);

        return $this->response([
            'resolveLike' => $entityName,
            'result' => $result,
        ]);
    }

    private function response(array $payload = []): Response
    {
        return $this->asJson($payload);
    }

    private function resolve(string $code, $location): array
    {
        if (str_starts_with($code, 'PI')) {
            return ['personal', $code];
        }
        if (str_starts_with($code, 'TI')) {
            return ['task', $code];
        }
        $part = Part::find()->where(['serial' => $code])->one();
        if ($part) {
            return ['part', $part];
        }
        $model = Model::find()->where(['partno' => $code])->one();
        if ($model) {
            return ['model', $model];
        }
        $order = Order::find()->where(['name' => $code])->one();
        if ($order) {
            return ['order', $order];
        }
        $destination = Move::perform('get-directions', ['name_like' => $code, 'limit' => 1], ['batch' => true]);
        if (!empty($destination)) {
            return ['destination', reset($destination)];
        }

        return [null, null];
    }
}
