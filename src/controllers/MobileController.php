<?php
declare(strict_types=1);

namespace hipanel\modules\stock\controllers;

use hipanel\components\SettingsStorage;
use hipanel\filters\EasyAccessControl;
use hipanel\modules\server\models\Hub;
use hipanel\modules\stock\models\Model;
use hipanel\modules\stock\models\Move;
use hipanel\modules\stock\models\Order;
use hipanel\modules\stock\models\Part;
use hipanel\modules\stock\Module;
use hiqdev\hiart\Exception;
use hiqdev\hiart\ResponseErrorException;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\JsonParser;
use yii\web\Response;
use yii\web\User;

class MobileController extends Controller
{
    private const KEY = 'mobile-stock-sessions';

    public function __construct(
        $id,
        Module $module,
        private readonly User $user,
        private readonly SettingsStorage $storage,
        array $config = []
    )
    {
//        $this->request->parsers = [
//            'application/json' => JsonParser::class,
//        ];
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
        $sessions = $this->storage->getBounded(self::KEY);

        return $this->response($sessions);
    }

    public function actionCreateSession(): Response
    {
        $time = date('c');

        return $this->response(['id' => $time, 'name' => $time]);
    }

    public function actionSetSession(): Response
    {
        sleep(3);

        return $this->response([]);
    }

    public function actionDeleteSession(): Response
    {
        sleep(3);

        return $this->response([]);
    }

    public function actionMove(): Response
    {
        $requestData = $this->request->post();
        $data = [];
        foreach ($requestData['parts'] as $part) {
            $data[$part['id']] = [
                'id' => $part['id'],
                'src_id' => $part['dst_id'],
                'dst_id' => $requestData['destination']['id'],
                'type' => 'install',
                'remote_ticket' => '',
                'hm_ticket' => '',
                'descr' => '',
            ];
        }
        try {
            Part::perform('move', $data, ['batch' => true]);

            return $this->response(['status' => 'success']);
        } catch (ResponseErrorException $e) {
            return $this->response(['status' => 'error', 'errorMessage' => $e->getMessage()]);
        }
    }

    public function actionSendMessage(): Response
    {
        sleep(3);

        return $this->response(['status' => 'success']);
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
        $destination = Move::perform('get-directions', ['name' => $code, 'limit' => 1], ['batch' => true]);
        if (!empty($destination)) {
            return ['destination', reset($destination)];
        }
        $part = Part::find()->where(['serial' => $code])->one();
        $model = Model::find()->where(['partno' => $part->partno ?? $code, 'with_counters' => true])->one();
//        $part = Part::find()->where(['serial' => $code])->one();
//        if ($part) {
//            return ['part', $part];
//        }
//        $model = Model::find()->where(['partno' => $code])->one();
//        if ($model) {
//            return ['model', $model];
//        }
//        $order = Order::find()->where(['name' => $code])->one();
//        if ($order) {
//            return ['order', $order];
//        }
        return [null, null];
    }
}
