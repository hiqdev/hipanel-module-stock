<?php

declare(strict_types=1);

namespace hipanel\modules\stock\widgets;

use hipanel\modules\stock\forms\FastMoveForm;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hipanel\modules\stock\widgets\combo\SourceCombo;
use Yii;
use yii\base\Widget;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

final class FastMoveModal extends Widget
{
    public function init(): void
    {
        $this->view->on(View::EVENT_END_BODY, function () {
            $model = new FastMoveForm();
            Modal::begin([
                'id' => $this->getId(),
                'header' => Html::tag('h4', Yii::t('hipanel:stock', 'Fast move'), ['class' => 'modal-title']),
                'toggleButton' => false,
            ]);

            $form = ActiveForm::begin([
                'action' => Url::to(['@part/fast-move']),
            ]);
            $fixSelect2InBootstrapModalOptions = [
                'pluginOptions' => [
                    'select2Options' => [
                        'dropdownParent' => new JsExpression('$("#' . $this->getId() . '")'),
                    ],
                ],
            ];

            echo $form->field($model, 'partno')->widget(PartnoCombo::class, $fixSelect2InBootstrapModalOptions);
            echo $form->field($model, 'src_id')->widget(SourceCombo::class, $fixSelect2InBootstrapModalOptions);
            echo $form->field($model, 'quantity')->input('number', ['min' => 1]);
            echo $form->field($model, 'dst')->textarea()->hint(Yii::t('hipanel:stock', 'Type server names, delimited with a space, comma or a new line'));

            echo Html::submitButton(Yii::t('hipanel:stock', 'Move'), ['class' => 'btn btn-success btn-block']);

            ActiveForm::end();

            Modal::end();
        });
    }

    public function run(): string
    {
        if (!Yii::$app->user->can('move.create')) {
            return '';
        }

        return Html::a(Yii::t('hipanel:stock', 'Fast move'), ['#'], [
            'data' => ['toggle' => 'modal', 'target' => '#' . $this->getId()],
            'class' => 'btn btn-sm btn-default',
        ]);
    }
}
