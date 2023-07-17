<?php
declare(strict_types=1);

namespace hipanel\modules\stock\controllers;

use yii\web\Controller;

class MobileController extends Controller
{
    public function actionIndex(): string
    {
        $this->layout = 'mobile-layout';

        return $this->render('index');
    }
}
