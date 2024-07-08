<?php

use hipanel\helpers\Url;
use hipanel\modules\stock\widgets\combo\DestinationCombo;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hipanel\modules\stock\widgets\DisposalField;
use hipanel\modules\stock\widgets\PartSourceWidget;
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
    <?php foreach ($models as $idx => $model) : ?>
        <?= Html::activeHiddenInput($model, "[$idx]id") ?>
        <?= Html::activeHiddenInput($model, "[$idx]move_type") ?>
        <div class="item">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Html::encode($model->title) ?></h3>
                </div>
                <div class="box-body">
                    <div class="row input-row margin-bottom">
                        <div class="col-md-6">
                            <?= PartSourceWidget::widget(['index' => $idx, 'model' => $model]) ?>
                            <?= $form->field($model, "[$idx]dst_id")->widget(DestinationCombo::class) ?>
                            <?= $form->field($model, "[$idx]partno")->widget(PartnoCombo::class) ?>
                            <?= DisposalField::widget(['index' => $idx, 'form' => $form, 'model' => $model]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field(
                                $model,
                                "[$idx]serial",
                                [
                                    'template' => "{label}<div class='input-group'>{old_serial}{input}</div>\n{hint}\n{error}",
                                    'parts' => [
                                        '{old_serial}' => Html::tag('span',
                                            Yii::t('hipanel:stock', '{0}', $model->serial),
                                            ['class' => 'input-group-addon bg-gray']),
                                    ],
                                ]) ?>
                            <?= $form->field($model, "[$idx]move_descr")->textarea() ?>
                        </div>
                    </div>
                </div>
            </div>
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
