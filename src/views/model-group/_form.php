<?php

use hipanel\helpers\Url;
use hipanel\modules\stock\models\ModelGroup;
use hipanel\widgets\DynamicFormWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/** @var ModelGroup $model */
/** @var ModelGroup[] $models */

?>

<?php $form = ActiveForm::begin([
    'id' => 'model-groups-form',
    'action' => Url::toRoute(['@model-group/' . ($model->scenario === 'copy' ? 'create' : $model->scenario)]),
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
]) ?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper',
    'widgetBody' => '.container-items',
    'widgetItem' => '.item',
    'limit' => 99,
    'min' => 1,
    'insertButton' => '.add-item',
    'deleteButton' => '.remove-item',
    'model' => reset($models),
    'formId' => 'model-groups-form',
    'formFields' => [
        'name',
        'descr',
    ],
]) ?>
<div class="container-items">
    <?php foreach ($models as $i => $model) : ?>
        <?php
        if ($model->scenario === 'update') {
            echo Html::activeHiddenInput($model, "[$i]id");
        }
        ?>
        <div class="item">
            <div class="box box-widget">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                    <?php if ($model->scenario === 'create') : ?>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool remove-item">
                                <i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool add-item"><i class="fa fa-plus"></i></button>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <?= $form->field($model, "[$i]name") ?>
                        </div>

                        <div class="col-md-3">
                            <?= $form->field($model, "[$i]descr")->textarea(['rows' => 1]) ?>
                        </div>

                        <?php foreach ($model->supportedLimitTypes as $type => $label) : ?>
                            <div class="col-md-1">
                                <?= $form->field($model, "[$i]data[limit][$type]")->label($label) ?>
                            </div>
                        <?php endforeach; ?>
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
        <?= Html::a(Yii::t('hipanel', 'Cancel'), ['@model-group/index'], ['class' => 'btn btn-default']) ?>
    </div>
</div>

<?php ActiveForm::end() ?>
