<?php

use hipanel\helpers\Url;
use hipanel\widgets\ArraySpoiler;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin([
    'id' => 'set-price-form',
    'action' => Url::toRoute('set-price'),
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => 'set-price']),
]) ?>

    <div class="panel panel-default">
        <div class="panel-heading"><?= Yii::t('app', 'Set price') ?></div>
        <div class="panel-body">
            <?= ArraySpoiler::widget([
                'data' => $models,
                'visibleCount' => count($models),
                'formatter' => function ($model) {
                    return $model->partno . sprintf(' (%s)', $model->serial);
                },
                'delimiter' => ',&nbsp; ',
            ]); ?>
        </div>
    </div>

<?php foreach ($models as $model) : ?>
    <?= Html::activeHiddenInput($model, "[$model->id]id") ?>
<?php endforeach; ?>
<?= $form->field($model, 'price')->textInput(['value' => '', 'placeholder' => '0.00', 'name' => 'price']) ?>
<hr>
<?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end() ?>