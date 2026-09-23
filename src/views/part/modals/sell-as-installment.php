<?php

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\stock\forms\PartSellAsInstallmentForm;
use hipanel\widgets\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\JsExpression;

/** @var array $partsByModelType */
/** @var PartSellAsInstallmentForm $model */

?>
<?php $form = ActiveForm::begin([
    'id' => 'part-sell-as-installment-form',
    'validateOnChange' => false,
    'enableAjaxValidation' => true,
    'validationUrl' => \yii\helpers\Url::toRoute(['validate-sell-as-installment-form', 'scenario' => 'default']),
    'options' => [
        'autocomplete' => 'off',
    ],
]) ?>

<div id="part-sell-fields" class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'client_id')->widget(ClientCombo::class, [
            'pluginOptions' => [
                'select2Options' => [
                    'dropdownParent' => new JsExpression('$(".modal.in")'),
                ],
            ],
        ]) ?>
        <?= $form->field($model, 'currency')->dropDownList($currencyOptions) ?>
        <?= $form->field($model, 'monthly_sum')->textInput() ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'since')->widget(DatePicker::class) ?>
        <?= $form->field($model, 'months')->textInput(['type' => 'number', 'min' => 1]) ?>
        <?= $form->field($model, 'reason')->textInput() ?>
    </div>
</div>

<div class="parts-for-sell panel panel-default">
    <?= Html::tag('div', Yii::t('hipanel:stock', 'Parts'), ['class' => 'panel-heading']) ?>
    <?php foreach ($partsByModelType as $modelType => $typeParts) : ?>
        <table class="table">
            <thead>
            <tr>
                <th colspan="2"><?= mb_strtoupper(Yii::t('hipanel:stock', $modelType)) ?></th>
            </tr>
            </thead>
            <?php foreach (array_chunk($typeParts, 2) as $parts): ?>
                <tr>
                    <?php foreach ($parts as $part) : ?>
                        <td style="width: 50%">
                            <?= Html::activeHiddenInput($model, "ids[]", ['value' => $part->id]) ?>
                            <?= sprintf('%s @ %s', Html::a($part->title, ['@part/view', 'id' => $part->id], ['tabindex' => -1]), $part->dst_name) ?>
                        </td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
        </table>
    <?php endforeach ?>
</div>

<div class="row">
    <div class="col-xs-6 col-sm-8">
        <?= Html::submitButton(Yii::t('hipanel:stock', 'Sell'), ['class' => 'btn btn-success']) ?> &nbsp;
        <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
    </div>
</div>

<?php $form::end() ?>
