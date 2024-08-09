<?php

/**
 * @var \yii\web\View $this
 * @var Order $model
 */

use hipanel\modules\stock\models\Order;
use hipanel\modules\stock\widgets\combo\ContactCombo;
use hipanel\widgets\DateTimePicker;
use hipanel\widgets\Box;
use hipanel\widgets\FileInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<?php $form = ActiveForm::begin([
    'id' => 'order-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
]) ?>

<div class="row">
    <div class="col-md-6">

        <?php Box::begin() ?>

        <?php if (!$model->isNewRecord) : ?>
            <?= Html::activeHiddenInput($model, 'id') ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'type')->dropDownList($model->typeOptions) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'seller_id')->widget(ContactCombo::class) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'state')->dropDownList($model->stateOptions) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'buyer_id')->widget(ContactCombo::class) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'no') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'time')->widget(DateTimePicker::class, [
                    'clientOptions' => [
                        'todayBtn' => true,
                    ],
                    'options' => [
                        'value' => Yii::$app->formatter->asDatetime(new DateTime(), 'php:Y-m-d H:i:s'),
                    ],
                ]) ?>
            </div>
        </div>

        <?= $form->field($model, 'name')->textarea(['rows' => 3]) ?>

        <?php Box::end() ?>

    </div>

    <?php if ($model->fileCount < Order::MAX_FILES_COUNT) : ?>
        <div class="col-md-6">
            <?php Box::begin() ?>
            <?= $form->field($model, 'file[]')->widget(FileInput::class, [
                'options' => [
                    'multiple' => true,
                ],
                'pluginOptions' => [
                    'previewFileType' => 'any',
                    'showRemove' => true,
                    'showUpload' => false,
                    'initialPreviewShowDelete' => true,
                    'maxFileCount' => Order::MAX_FILES_COUNT - $model->fileCount,
                    'msgFilesTooMany' => Yii::t('hipanel', 'Number of files selected for upload ({n}) exceeds maximum allowed limit of {m}'),
                ],
            ]) ?>
            <?php Box::end() ?>
        </div>
    <?php endif ?>
</div>

<div class='row'>
    <div class='col-md-12 no'>
        <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?= Html::a(Yii::t('hipanel', 'Cancel'), ['@order/index'], ['class' => 'btn btn-default']) ?>
    </div>
</div>

<?php ActiveForm::end() ?>
