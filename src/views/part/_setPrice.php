<?php

use hipanel\helpers\Url;
use hipanel\widgets\ArraySpoiler;
use hipanel\widgets\AmountWithCurrency;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var \yii\base\Model $model
 * @var \yii\base\Model[] $models
 */

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
                [$partno, $serial, $src_name] = array_map(
                    fn ($el) => Html::encode($el),
                    [$model->partno, $model->serial, $model->src_name]
                );
                return sprintf('%s (%s/<b>%s</b>)', $partno, $serial, $src_name);
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
