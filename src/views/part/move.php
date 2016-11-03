<?php

use hipanel\helpers\Url;
use hipanel\modules\stock\widgets\combo\DestinationCombo;
use hipanel\modules\stock\widgets\combo\SourceCombo;
use hipanel\widgets\ArraySpoiler;
use hipanel\widgets\Box;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$scenario = $this->context->action->scenario;
$this->title = Yii::t('hipanel:stock', 'Bulk move');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:stock', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin([
    'id' => 'dynamic-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => 'move']),
]) ?>

<div class="container-items">
    <?php foreach ($models as $src_id => $group) { ?>
        <?php Box::begin() ?>
        <?php $model = reset($group); ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-2">
                        <label><?= Yii::t('hipanel:stock', 'Parts in move') ?>:</label>
                        <div class="well well-sm">
                            <?= ArraySpoiler::widget([
                                'data' => $group,
                                'visibleCount' => count($group),
                                'formatter' => function ($model) {
                                    return $model->partno . sprintf(' (%s)', $model->serial);
                                },
                                'delimiter' => ',&nbsp; ',
                            ]); ?>
                            <div>
                                <?php foreach ($group as $model) { ?>
                                    <?= Html::activeHiddenInput($model, "[$src_id]id[]", ['value' => $model->id]); ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="row">
                            <div class="col-lg-4">
                                <?= $form->field($model, "[$src_id]src_id")->widget(SourceCombo::class, [
                                    'inputOptions' => [
                                        'readonly' => true
                                    ],
                                ]) ?>
                            </div>
                            <div class="col-lg-4">
                                <?= $form->field($model, "[$src_id]dst_id")->widget(DestinationCombo::class) ?>
                            </div>
                            <div class="col-lg-4">
                                <?= $form->field($model, "[$src_id]type")->dropDownList($types) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <?= $form->field($model, "[$src_id]remotehands")->dropDownList($remotehands) ?>
                            </div>
                            <div class="col-lg-4">
                                <?= $form->field($model, "[$src_id]remote_ticket")->textInput() ?>
                            </div>
                            <div class="col-lg-4">
                                <?= $form->field($model, "[$src_id]hm_ticket")->textInput() ?>
                            </div>
                        </div>
                        <?= $form->field($model, "[$src_id]descr")->textarea() ?>
                    </div>
                </div>
            </div>
        </div>
        <?php Box::end() ?>
    <?php } ?>

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
</div>
