<?php

namespace hipanel\modules\stock\actions;

use Exception;
use hipanel\modules\stock\models\HardwareSettings;
use hipanel\modules\stock\widgets\HardwareSettingsDetail;
use Yii;
use yii\base\Action;

class HardwareSettingsAction extends Action
{
    public function run()
    {
        $request = $this->controller->request;
        $session = Yii::$app->session;
        $id = $request->get('id');
        $modelType = $request->get('type');
        $model = new HardwareSettings(['scenario' => 'set']);
        if ($request->isPost && !$request->isAjax && $model->load($request->post())) {
            try {
                HardwareSettings::perform('set-hardware-settings', $model->getAttributes());
                $session->addFlash('success', Yii::t('hipanel:stock', 'Settings has been changed'));
            } catch (Exception $e) {
                $session->addFlash('error', $e->getMessage());
            }

            return $this->controller->redirect(['view', 'id' => $model->id]);
        }
        $model = HardwareSettings::find()
            ->action('get-hardware-settings')
            ->addOption('batch', false)
            ->where(['id' => $id, 'model_type' => $modelType])
            ->one();

        if ($request->isPost && $request->isAjax) {
            return HardwareSettingsDetail::widget(['id' => $model->id, 'type' => $model->model_type, 'props' => $model->props]);
        }

        return $this->controller->renderAjax('@vendor/hiqdev/hipanel-module-stock/src/views/model/modals/hardware-settings', [
            'model' => $model,
        ]);
    }
}
