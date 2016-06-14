<?php
use hipanel\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>

<?php $form = ActiveForm::begin([
    'id' => 'update-serial-form',
    'action' => Url::toRoute('update-serial'),
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => 'update-serial']),
]) ?>

<?php foreach ($models as $model) : ?>
    <?= Html::activeHiddenInput($model, "[$model->id]id") ?>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, "[$model->id]partno")->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, "[$model->id]serial") ?>
        </div>
    </div>
<?php endforeach; ?>
<hr>
<?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end() ?>