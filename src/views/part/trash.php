<?php

use hipanel\modules\stock\models\Part;
use hipanel\modules\stock\widgets\combo\TrashDestinationDropDownList;
use hipanel\widgets\ArraySpoiler;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var array $moveTypes */
/** @var array $suppliers */

$this->title = Yii::t('hipanel:stock', 'Trash');
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

<?php foreach ($models as $model) : ?>
    <?= Html::activeHiddenInput($model, "partId2srcId[$model->id]", ['value' => $model->src_id]) ?>
<?php endforeach ?>

<div class="box">
    <div class="box-body">
        <div class="row input-row margin-bottom">
            <div class="col-md-6">
                <?= Html::textarea('partNames', ArraySpoiler::widget([
                    'data' => array_map(fn(Part $part) => sprintf('%s (%s)', $part->partno, $part->serial), $models),
                    'visibleCount' => count($models),
                    'delimiter' => "\n",
                ]), ['class' => 'form-control', 'disabled' => true, 'rows' => 20]) ?>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, "dst_id")->widget(TrashDestinationDropDownList::class) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, "move_type")->dropDownList($moveTypes) ?>
                    </div>
                </div>
                <?= $form->field($model, "remotehands") ?>
                <?= $form->field($model, "remote_ticket") ?>
                <?= $form->field($model, "hm_ticket") ?>
                <?= $form->field($model, "move_descr")->textarea(['rows' => 5]) ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 no">
        <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
    </div>
</div>

<?php ActiveForm::end() ?>
