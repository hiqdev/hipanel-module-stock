<?php

use hipanel\helpers\Url;
use hipanel\widgets\ArraySpoiler;
use hipanel\widgets\AmountWithCurrency;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin([
    'id' => 'set-price-form',
    'action' => Url::toRoute('set-price'),
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => 'set-price']),
]) ?>

<div class="panel panel-default">
    <div class="panel-heading"><?= Yii::t('hipanel:stock', 'Set price') ?></div>
    <div class="panel-body">
        <?= ArraySpoiler::widget([
            'data' => $models,
            'visibleCount' => count($models),
            'formatter' => function ($model) {
                return $model->partno . sprintf(' (%s)', $model->serial);
            },
            'delimiter' => ',&nbsp; ',
        ]) ?>
    </div>
</div>

<?php foreach ($models as $model) : ?>
    <?= Html::activeHiddenInput($model, "[$model->id]id") ?>
<?php endforeach ?>
<div class="<?= AmountWithCurrency::$widgetClass ?>">
    <?= $form->field($model, 'price')->widget(AmountWithCurrency::class, [
        'currencyAttributeName' => 'currency',
        'currencyAttributeOptions' => [
            'items' => $this->context->getCurrencyTypes(),
        ],
    ]) ?>
    <?= $form->field($model, 'currency', ['template' => '{input}{error}'])->hiddenInput() ?>
</div>
<hr>
<?= Html::submitButton(Yii::t('hipanel', 'Submit'), ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end() ?>
