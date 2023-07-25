<?php
declare(strict_types=1);

namespace hipanel\modules\stock\controllers;

use hipanel\components\SettingsStorage;
use hipanel\filters\EasyAccessControl;
use hipanel\helpers\ArrayHelper;
use hipanel\modules\server\models\Hub;
use hipanel\modules\stock\models\Model;
use hipanel\modules\stock\models\Move;
use hipanel\modules\stock\models\Order;
use hipanel\modules\stock\models\Part;
use hipanel\modules\stock\Module;
use hiqdev\hiart\ResponseErrorException;
use yii\filters\VerbFilter;
use yii\web\Controller;
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
        $responseTemplate = ['resolveLike' => null, 'result' => null];
        if (str_starts_with($code, 'PI')) {
            $responseTemplate['resolveLike'] = 'personal';
            $responseTemplate['result'] = $code;
        }
        if (str_starts_with($code, 'TI')) {
            $responseTemplate['resolveLike'] = 'task';
            $responseTemplate['result'] = $code;
        }
        $destination = Move::perform('get-directions', ['name' => $code, 'limit' => 1], ['batch' => true]);
        if (!empty($destination)) {
            $responseTemplate['resolveLike'] = 'destination';
            $responseTemplate['result'] = reset($destination);
        }
        $part = Part::find()->where(['serial' => $code])->one();
        if ($part && $part->device_location !== $location) {
            return $this->response($responseTemplate);
        }
        $model = Model::find()->where(['partno' => $code])->one();
        $order = Order::find()->where(['name' => $code])->one();
        $resolvedName = match (true) {
            !empty($part) => 'part',
            !empty($model) => 'model',
            !empty($order) => 'order',
            default => null,
        };
        $queryConditions = match (true) {
            !empty($part) => [
                'parts' => ['model_id' => $part->model_id], // , 'device_location' => $location
                'models' => ['id' => $part->model_id],
                'orders' => ['id' => $part->order_id],
            ],
            !empty($model) => [
                'parts' => ['model_id' => $model->id], // , 'device_location_like' => $location
            ],
            !empty($order) => ['parts' => ['order_id' => $order->id]], // , 'device_location_like' => $location
            default => [],
        };
        if (!empty($queryConditions)) {
            $parts = Part::find()->where($queryConditions['parts'])->limit(-1)->all();
            $parts = array_filter($parts, static fn($part) => $part->device_location === $location);
            if (!isset($queryConditions['models'])) {
                $queryConditions['models'] = [
                    'id_in' => array_unique(array_filter(ArrayHelper::getColumn($parts,
                        'model_id'))),
                ];
            }
            $models = $model ? [$model] : Model::find()->where($queryConditions['models'])->limit(-1)->all();
            if (!isset($queryConditions['orders'])) {
                $queryConditions['orders'] = [
                    'id_in' => array_unique(array_filter(ArrayHelper::getColumn($parts,
                        'order_id'))),
                ];
            }
            $orders = $order ? [$order] : Order::find()->where($queryConditions['orders'])->limit(-1)->all();

            $responseTemplate['resolveLike'] = $resolvedName;
            $responseTemplate['result'] = [
                'parts' => $parts,
                'models' => $models,
                'orders' => $orders,
            ];
        }

        return $this->response($responseTemplate);
    }

    private function response(array $payload = []): Response
    {
        return $this->asJson($payload);
    }
}
