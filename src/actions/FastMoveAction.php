<?php

declare(strict_types=1);

namespace hipanel\modules\stock\actions;

use Exception;
use hipanel\modules\stock\forms\FastMoveForm;
use hipanel\modules\stock\models\Part;
use RuntimeException;
use Yii;
use yii\base\Action;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Session;

final class FastMoveAction extends Action
{
    private Session $session;

    public function __construct($id, Controller $controller, Session $session, array $config = [])
    {
        parent::__construct($id, $controller, $config);
        $this->session = $session;
    }

    public function run(): Response
    {
        $form = new FastMoveForm();
        $controller = $this->controller;
        try {
            if ($controller->request->isPost && $form->load($controller->request->post()) && $form->validate()) {
                Part::batchPerform('bulk-move', $form->getAttributes());
                $this->session->setFlash('success', Yii::t('hipanel:stock', 'Parts have been moved'));
            } else if ($form->hasErrors()) {
                $errors = $form->getFirstErrors();
                throw new RuntimeException(reset($errors));
            } else {
                throw new RuntimeException('The from data is broken, try again please');
            }
        } catch (Exception $e) {
            $this->session->setFlash('error', $e->getMessage());
        }

        return $controller->redirect(['@part/index']);
    }
}
