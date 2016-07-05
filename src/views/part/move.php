<?php

use hipanel\helpers\Url;
use hipanel\modules\stock\widgets\combo\DestinationCombo;
use hipanel\widgets\ArraySpoiler;
use hipanel\widgets\Box;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$scenario = $this->context->action->scenario;
$this->title = Yii::t('hipanel/stock', 'Bulk move');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin([
    'id' => 'dynamic-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => reset($models)->isNewRecord ? 'create' : 'update']),
]) ?>

<div class="container-items">
    <?php foreach ($models as $i => $model) : ?>
        <?= Html::activeHiddenInput($model, "[$i]id"); ?>
    <?php endforeach; ?>
        <?php Box::begin() ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                         <div class="well well-sm">
                             <h4><?= Yii::t('hipanel/stock', 'Parts in move')?>:</h4>
                             <br>
                             <?= ArraySpoiler::widget([
                                'data' => $models,
                                'visibleCount' => count($models),
                                'formatter' => function ($model) {
                                    return $model->partno . sprintf(' (%s)', $model->serial);
                                },
                                'delimiter' => ',&nbsp; ',
                            ]); ?>
                         <div>
                        <?php foreach ($models as $model) : ?>
                            <?= Html::activeHiddenInput($model, "[$i]id", ['value' => $ids]); ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <?php $model->dst_id = null; ?>
                        <?= $form->field($model, "[$i]dst_id")->widget(DestinationCombo::className()) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, "[$i]move_type")->dropDownList($types) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, "[$i]remotehands")->dropDownList($remotehands) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, "[$i]remote_ticket")->textInput() ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, "[$i]hm_ticket")->textInput() ?>
                    </div>
                </div>

                <?= $form->field($model, "[$i]descr")->textarea() ?>
            </div>
        </div>
        <?php Box::end() ?>
</div>

<?php Box::begin(['options' => ['class' => 'box-solid']]) ?>
<div class="row">
    <div class="col-md-12 no">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?= Html::button(Yii::t('app', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
    </div>
</div>
<?php Box::end() ?>
<?php ActiveForm::end() ?>

