<?php

use hipanel\modules\stock\widgets\PartSourceWidget;
use hipanel\widgets\Box;
use hipanel\widgets\DynamicFormWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('hipanel:stock', 'Reserve');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:stock', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$scenario = $this->context->action->scenario;

$this->registerJs(<<<JS
(() => {
  function bind(source, idContains) {
    source.addEventListener("keyup", (e) => {
      [].forEach.call(document.querySelectorAll(".container-items .form-control"), input => {
        if (input.matches("[id$='" + idContains + "']")) {
          input.value = e.target.value;
        }
      });
    });
  }
  bind(document.getElementById("bulkReserve"), "-reserve");
  bind(document.getElementById("bulkMoveDescription"), "-descr");
})();
JS
);

?>

<?php $form = ActiveForm::begin([
    'id' => 'dynamic-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $scenario]),
]) ?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
    'widgetBody' => '.container-items', // required: css class selector
    'widgetItem' => '.item', // required: css class
    'limit' => 99, // the maximum times, an element can be cloned (default 999)
    'min' => 1, // 0 or 1 (default 1)
    'insertButton' => '.add-item', // css class
    'deleteButton' => '.remove-item', // css class
    'model' => reset($models),
    'formId' => 'dynamic-form',
    'formFields' => [
        'part',
    ],
]) ?>

<div class="row">
    <div class="col-md-3 col-md-offset-6">
        <div class="form-group">
            <input type="text" class="form-control" id="bulkReserve" placeholder="Bulk reserve">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <input type="text" class="form-control" id="bulkMoveDescription" placeholder="Bulk move description">
        </div>
    </div>
</div>

<div class="container-items">
    <?php foreach ($models as $i => $model) : ?>
        <?php
        // necessary for update action.
        $model->scenario = $scenario;
        echo Html::activeHiddenInput($model, "[$i]id");
        ?>
        <div class="item">
            <?php Box::begin() ?>
            <div class="row">
                <div class="col-md-2">
                    <?= $form->field($model, "[$i]partno")->textInput(['readonly' => true]) ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, "[$i]serial")->textInput(['readonly' => true]) ?>
                </div>
                <div class="col-md-2">
                    <?= PartSourceWidget::widget([
                        'index' => $i,
                        'model' => $model,
                    ]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, "[$i]reserve")->textInput(['readonly' => $scenario === 'unreserve']) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, "[$i]descr")->textInput([])->label(Yii::t('hipanel:stock',
                        'Move description')); ?>
                </div>
            </div>
            <?php Box::end() ?>
        </div>
    <?php endforeach; ?>
</div>
<?php DynamicFormWidget::end() ?>
<div class="row">
    <div class="col-md-12 no">
        <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>

