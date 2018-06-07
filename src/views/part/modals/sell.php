<?php

/** @var array $parts */

/** @var array $currencyOptions */

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\stock\widgets\combo\ContactCombo;
use hipanel\widgets\DateTimePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->registerJs("
$('#partsellform-client_id').on('select2:select', function (e) {
    var clientInput = $('#partsellform-client_id option:selected');
    var selectedClientId = clientInput.val();
    var selectedClientName = clientInput.text().trim();
    jQuery.post('/client/contact/search', {return: ['id', 'name', 'email'], select: 'min', client: selectedClientName}).done(function (contacts) {
        var autoContact = contacts.filter(function (contact) {
            return contact.id === selectedClientId;    
        });
        if (autoContact.length > 0) {
            $('#partsellform-contact_id')
                .empty()
                .append('<option value=\"' + autoContact[0]['id'] + '\">'+ autoContact[0]['name']+ '</option>')
                .val(autoContact[0]['id'])
                .trigger('change');
        } else {
            $('#partsellform-contact_id').empty();
        }
    });
});
");

?>

<?php $form = ActiveForm::begin([
    'options' => [
        'id' => $model->scenario . '-form',
    ],
    'validateOnChange' => false,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-sell-form']),
]) ?>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'client_id')->widget(ClientCombo::class) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'contact_id')->widget(ContactCombo::class) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'time')->widget(DateTimePicker::class) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'currency')->dropDownList($currencyOptions) ?>
    </div>
    <div class="col-md-12">
        <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
    </div>
</div>

<div class="well well-sm">
    <legend><?= Yii::t('hipanel:stock', 'Parts') ?></legend>
    <?php foreach (array_chunk($parts, 2) as $row) : ?>
        <div class="row">
            <?php foreach ($row as $part) : ?>
                <div class="col-md-6">
                    <?= Html::activeHiddenInput($model, "ids[]", ['value' => $part->id]) ?>
                    <?= $form->field($model, "sums[$part->id]")->textInput([
                        'placeholder' => Yii::t('hipanel:stock', 'Part price'),
                    ])->label(sprintf('%s | %s', $part->title, $part->dst_name)) ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</div>

<?= Html::submitButton(Yii::t('hipanel', 'Create'), ['class' => 'btn btn-success']) ?> &nbsp;
<?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>

<?php $form::end() ?>

