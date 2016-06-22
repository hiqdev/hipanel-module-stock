<?php

use hipanel\helpers\Url;
use hipanel\modules\stock\widgets\combo\DestinationCombo;
use hipanel\modules\stock\widgets\combo\SourceCombo;
use hipanel\widgets\Box;
use hipanel\widgets\DynamicFormWidget;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = Yii::t('hipanel/stock', 'Copy');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$model->scenario = $scenario = 'copy';
?>


<?php $form = ActiveForm::begin([
    'id' => 'dynamic-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
    'action' => '@part/create'
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
        'partno',
        'serials',
        'src_id',
        'dst_id',
        'move_type',
        'descr',
        'price',
        'currency',
    ],
]) ?>

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
                <div class="col-md-4">
                    <?= $form->field($model, "[$i]partno")->textInput() ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, "[$i]serial")->textInput() ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, "[$i]src_id")->widget(SourceCombo::class) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, "[$i]dst_id")->widget(DestinationCombo::class) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, "[$i]descr")->textarea() ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, "[$i]price") ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, "[$i]currency")->dropDownList($model->transformToSymbols(array_keys($currencyTypes))) ?>
                </div>
            </div>
            <?php Box::end() ?>
        </div>
    <?php endforeach; ?>
</div>
<?php DynamicFormWidget::end() ?>
<?php Box::begin(['options' => ['class' => 'box-solid']]) ?>
<div class="row">
    <div class="col-md-12 no">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-default']) ?>
        &nbsp;
        <?= Html::button(Yii::t('app', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
    </div>
</div>
<?php Box::end() ?>
<?php ActiveForm::end() ?>

