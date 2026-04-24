<?php

declare(strict_types=1);

namespace hipanel\modules\stock\actions;

use hipanel\modules\stock\models\InstallmentPlan;
use Yii;
use yii\base\Action;

class InstallmentPlanProcessAction extends Action
{
    public function run()
    {
        if (Yii::$app->request->isPost) {
            try {
                InstallmentPlan::perform('process', [], ['batch' => true]);
                Yii::$app->session->setFlash('success', Yii::t('hipanel:stock', 'Installment plans have been processed'));
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->controller->redirect(['index']);
    }
}
