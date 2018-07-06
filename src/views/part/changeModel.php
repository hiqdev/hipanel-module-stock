<?php

use hipanel\helpers\Url;
use hipanel\modules\stock\widgets\combo\ModelCombo;
use hipanel\widgets\ArraySpoiler;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = Yii::t('hipanel:stock', 'Change model');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:stock', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?php $form = ActiveForm::begin([
    'id' => 'change-model-form',
    'action' => Url::toRoute('change-model'),
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => 'change-model']),
]) ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-2">
                <?php $model->model_id = null; print $form->field($model, 'model_id')->widget(ModelCombo::class) ?>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <?= ArraySpoiler::widget([
            'data' => $models,
            'visibleCount' => count($models),
            'formatter' => function ($model) {
                return Html::tag('span', $model->title, ['class' => 'label label-default']);
            },
            'delimiter' => ',&nbsp; ',
        ]) ?>
    </div>
</div>

<?php foreach ($models as $model) : ?>
    <?= Html::activeHiddenInput($model, "[$model->id]id") ?>
    <?= Html::activeHiddenInput($model, "[$model->id]model_id") ?>
    <?= Html::activeHiddenInput($model, "[$model->id]price") ?>
    <?= Html::activeHiddenInput($model, "[$model->id]currency") ?>
    <?= Html::activeHiddenInput($model, "[$model->id]company_id") ?>
    <?= Html::activeHiddenInput($model, "[$model->id]serial") ?>
<?php endforeach ?>


<?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
&nbsp;
<?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>

<?php ActiveForm::end() ?>

