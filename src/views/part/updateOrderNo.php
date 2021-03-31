<?php

use hipanel\helpers\Url;
use hipanel\modules\stock\models\Part;
use hipanel\widgets\DynamicFormWidget;
use hipanel\modules\stock\widgets\combo\OrderCombo;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

/** @var Part[] $models */
/** @var Part $model */

$this->title = Yii::t('hipanel:stock', 'Update Order No.');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:stock', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$partsByFirstMoveId = ArrayHelper::index($models, null, ['first_move_id']);

?>

<?php $form = ActiveForm::begin([
    'id' => 'update-order-no-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => 'update-order-no']),
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
    'formId' => 'update-order-no-form',
    'formFields' => [
        'first_move_id',
        'first_move',
    ],
]) ?>
<p class="text-warning">
    <?= Yii::t('hipanel:stock', 'This operation will change the description of all the Moves and will affect other parts of the Move, even if they were not selected.') ?>
</p>
<div class="container-items">
    <?php foreach ($partsByFirstMoveId as $firstMoveId => $parts) : ?>
        <div class="item">
            <?= Html::activeHiddenInput($model, "[$firstMoveId]first_move_id") ?>
            <div class="box">
                <div class="box-header with-border">
                    <?= $form->field($model, "[$firstMoveId]order_id")->widget(OrderCombo::class, [
                        'pluginOptions' => [
                            'select2Options' => $model->isNewRecord ? [] : [
                                'templateSelection' => new \yii\web\JsExpression("
                                    function (data, container) {
                                        return data.text;
                                    }
                                "),
                            ],
                        ],
                    ]) ?>
                </div>
                <div class="box-body">
                    <?php foreach ($parts as $part) : ?>
                        <?= Html::activeHiddenInput($part, "[$firstMoveId]ids[]", ['value' => $part->id]) ?>
                        <?= Html::tag('span', Html::encode($part->title), ['class' => 'label label-primary']) ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php DynamicFormWidget::end() ?>
<div class="row">
    <div class="col-md-12">
        <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
