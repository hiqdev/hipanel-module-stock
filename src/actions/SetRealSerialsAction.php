<?php

namespace hipanel\modules\stock\actions;

use Exception;
use hipanel\helpers\StringHelper;
use hipanel\helpers\Url;
use hipanel\modules\stock\models\Part;
use Yii;
use yii\base\Action;
use yii\bootstrap\ActiveForm;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;
use yii\web\Session;

class SetRealSerialsAction extends Action
{
    private Session $session;

    private Request $request;

    public function __construct($id, Controller $controller, Session $session, array $config = [])
    {
        parent::__construct($id, $controller, $config);
        $this->session = $session;
        $this->request = $this->controller->request;
    }

    public function run()
    {
        if ($this->request->isPost) {
            if ($this->request->isAjax) {
                return $this->validateForm();
            }

            return $this->saveSerials();
        }

        return $this->showModal();
    }

    private function saveSerials(): Response
    {
        $model = $this->getModel();
        $model->load($this->request->post());
        try {
            $payload = [];
            foreach (array_combine($model->ids, $model->getExtractedSerials()) as $id => $serial) {
                $payload[$id] = ['id' => $id, 'serial' => trim($serial)];
            }
            Part::batchPerform('set-serial', $payload);
            $this->session->addFlash('success', Yii::t('hipanel:stock', 'The serials have been changed'));
        } catch (Exception $e) {
            $this->session->addFlash('error', $e->getMessage());
        }

        return $this->controller->redirect($this->request->referrer);
    }

    private function showModal(): string
    {
        $ids = $this->request->get('selection', []);
        $model = $this->getModel();
        $parts = Part::find()->where(['ids' => $ids])->limit(-1)->all();
        $partsWithFakeSerial = array_filter($parts, static fn(Part $part): bool => (string)$part->id === (string)$part->serial
            && !$part->isTrashed()
            && !StringHelper::startsWith(mb_strtolower($part->dst_name), 'rma_')
        );
//        $partsWithFakeSerial = $parts;

        return $this->controller->renderAjax('@vendor/hiqdev/hipanel-module-stock/src/views/part/modals/set-real-serials', [
            'parts' => $partsWithFakeSerial,
            'model' => $model,
            'sownCount' => count($parts) - count($partsWithFakeSerial),
        ]);
    }

    private function validateForm(): array
    {
        $model = $this->getModel();
        $model->load($this->request->post());
        $this->controller->response->format = Response::FORMAT_JSON;

        return ActiveForm::validate($model);
    }

    private function getModel(): Part
    {
        return new Part(['scenario' => 'set-real-serials']);
    }
}
