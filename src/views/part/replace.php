<?php

use hipanel\helpers\Url;
use hipanel\modules\stock\widgets\combo\DestinationCombo;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hipanel\modules\stock\widgets\combo\SourceCombo;
use hipanel\modules\stock\widgets\PartSourceWidget;
use hipanel\widgets\Box;
use hipanel\widgets\DynamicFormWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('hipanel:stock', 'Replace');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:stock', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin([
    'id' => 'repair-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => reset($models)->scenario]),
]) ?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
    'widgetBody' => '.container-items', // required: css class selector
    'widgetItem' => '.item', // required: css class
    'limit' => count($models), // the maximum times, an element can be cloned (default 999)
    'min' => count($models), // 0 or 1 (default 1)
    'insertButton' => '.add-item', // css class
    'deleteButton' => '.remove-item', // css class
    'model' => reset($models),
    'formId' => 'dynamic-form',
    'formFields' => [
        'partno',
        'src_id',
        'dst_id',
        'serial',
        'move_type',
        'supplier',
        'first_move',
        'move_descr',
    ],
]) ?>
<div class="container-items">
    <?php foreach ($models as $i => $model) : ?>
        <?= Html::activeHiddenInput($model, "[$i]id") ?>
        <?= Html::activeHiddenInput($model, "[$i]move_type") ?>
        <div class="item">
            <?php Box::begin() ?>
            <div class="row input-row margin-bottom">
                <div class="col-md-6">
                    <?= PartSourceWidget::widget([
                        'index' => $i,
                        'model' => $model,
                    ]) ?>
                    <?= $form->field($model, "[$i]dst_id")->widget(DestinationCombo::class) ?>
                    <?= $form->field($model, "[$i]partno")->widget(PartnoCombo::class) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, "[$i]serial")->textInput() ?>
                    <div class="row">
                        <div class="col-md-4">
                            <?= $form->field($model, "[$i]supplier")->dropDownList($suppliers) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, "[$i]first_move") ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, "[$i]move_descr") ?>
                        </div>
                    </div>
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
        <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
    </div>
</div>
<?php Box::end() ?>
<?php ActiveForm::end() ?>
