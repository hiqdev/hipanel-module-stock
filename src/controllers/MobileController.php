<?php
declare(strict_types=1);

namespace hipanel\modules\stock\controllers;

use Exception;
use hipanel\components\I18N;
use hipanel\components\SettingsStorage;
use hipanel\filters\EasyAccessControl;
use hipanel\helpers\ArrayHelper;
use hipanel\modules\stock\models\Model;
use hipanel\modules\stock\models\Move;
use hipanel\modules\stock\models\Order;
use hipanel\modules\stock\models\Part;
use hipanel\modules\stock\Module;
use hiqdev\hiart\ActiveRecord;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use yii\web\User;

class MobileController extends Controller
{
    private const KEY = 'mobile-stock-manager-sessions';

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
                    'delete-session' => ['post'],
                    'resolve-code' => ['post'],
                    'complete' => ['post'],
                ],
            ],
        ]);
    }

    public function actionIndex(): string
    {
        $this->layout = 'mobile-manager';

        return $this->render('index');
    }

    public function actionCreateSession(): Response
    {
        $id = time();
        $data = $this->storage->getBounded(self::KEY);
        $data[$id] = ['id' => $id, 'name' => $id];

        $this->storage->setBounded(self::KEY, $data);

        return $this->response(['id' => $id, 'name' => $id]);
    }

    public function actionGetSessions(): Response
    {
        $sessions = $this->storage->getBounded(self::KEY);

        return $this->response(array_values($sessions));
    }

    public function actionSetSession($id): Response
    {
        $this->setSession($id);

        return $this->response();
    }

    public function actionDeleteSession($id): Response
    {
        $sessions = $this->storage->getBounded(self::KEY);
        unset($sessions[$id]);
        $this->storage->setBounded(self::KEY, $sessions);

        return $this->response();
    }

    public function actionComplete(): Response
    {
        $requestData = $this->request->post();
        $moveData = [];
        try {
            foreach ($requestData['parts'] as $part) {
                if (isset($requestData['destination'])) {
                    $moveData[$part['id']] = [
                        'id' => $part['id'],
                        'src_id' => $part['dst_id'],
                        'dst_id' => $requestData['destination']['id'],
                        'type' => 'install',
                        'hm_ticket' => $requestData['task'],
                        'descr' => $requestData['comment'],
                    ];
                }
            }
            if (!empty($moveData)) {
                Part::perform('move', $moveData, ['batch' => true]);
            }

            // todo: send message
            return $this->response(['status' => 'success']);
        } catch (Exception $e) {
            return $this->response(['status' => 'error', 'errorMessage' => $e->getMessage()]);
        }
    }

    public function actionGetLocations(): Response
    {
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
        if ($code === "PI::" . Yii::$app->user->identity->email) {
            $responseTemplate['resolveLike'] = 'personal';
            $responseTemplate['result'] = $code;
        }
        if (str_starts_with($code, 'https:')) {
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
        $model = Model::find()->where(['partno' => $code, 'show_deleted' => true])->one();
        $order = Order::find()->where(['name' => $code])->one();
        $resolveLike = match (true) {
            !empty($part) => 'part',
            !empty($model) => 'model',
            !empty($order) => 'order',
            default => null,
        };
        $queryConditions = match (true) {
            !empty($part) => [
                'parts' => ['model_id' => $part->model_id],
                'models' => ['id' => $part->model_id, 'show_deleted' => true],
                'orders' => ['id' => $part->order_id],
            ],
            !empty($model) => [
                'parts' => ['model_id' => $model->id],
            ],
            !empty($order) => ['parts' => ['order_id' => $order->id]],
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
                    'id_in' => array_unique(array_filter(ArrayHelper::getColumn($parts, 'order_id'))),
                ];
            }
            $orders = $order ? [$order] : Order::find()->where($queryConditions['orders'])->limit(-1)->all();

            $removeLegacyTags = static function (ActiveRecord $model) {
                /** @var I18N $i18n */
                $i18n = Yii::$app->i18n;
                foreach ($model->attributes as $attribute => $value) {
                    $model->{$attribute} = $i18n->removeLegacyLangTags($value);
                }

                return $model;
            };
            $parts = array_map($removeLegacyTags, $parts);
            $models = array_map($removeLegacyTags, $models);
            $responseTemplate['resolveLike'] = $resolveLike;
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

    private function setState(string|int $id): void
    {
        $state = $this->request->post();
        $data = $this->storage->getBounded(self::KEY);
        $data[$id] = $state;

        $this->storage->setBounded(self::KEY, $data);
    }
}
