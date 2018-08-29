<?php

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\stock\helpers\PartSort;
use hipanel\modules\stock\models\Part;
use hipanel\modules\stock\widgets\combo\ContactCombo;
use hipanel\modules\finance\widgets\combo\BillCombo;
use hipanel\widgets\DateTimePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var Part[] $parts
 * @var array $currencyOptions
 */
$this->registerCss('
.part-sell-total-container {
    text-transform: uppercase;
    font-weight: bold;
    display: inline-block;
    margin-bottom: 0;
    font-size: larger;
}
');

$this->registerJs("
// Auto select contact when client selected
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
// Calculate sum
$('.parts-for-sell :input, #partsellform-currency').change(function (event) {
    var form = $('#part-sell-form'), total = $('#part-sell-total');
    $.ajax({
        url: 'calculate-sell-total',
        type: 'POST',
        data: form.serialize(),
        dataType: 'json',
        beforeSend: function (jqXHR, settings) {
            total.html('<i class=\"fa fa-spinner fa-pulse fa-fw\"></i>');
        },
        success: function (resp) {
            total.text(resp.total);
        },
        error: function () {
            hipanel.notify.error('Error has occurred when try to count the parts total.');
        }
    });
});
// Toggle Bill exists button
$('#bill-exists-button').click(function (event) {
    $('#bill-exists-button').toggle();
    $('#bill-exists-field').toggle();
    event.preventDefault();
});
// Autoselect time when bill selected
$('#partsellform-bill_id').on('select2:select', function (e) {
    var billInput = $('#partsellform-bill_id option:selected');
    var selectedBillId = billInput.val();
    jQuery.post('/finance/bill/index', {return: ['id', 'time'], select: 'min', id: selectedBillId}).done(function (bills) {
        var auto = bills.filter(function (bill) {
            return bill.id === selectedBillId;    
        });
        if (auto.length > 0) {
            $('#partsellform-time').val(auto[0].time).attr({readonly: true}).parent().datetimepicker('remove');
        }
    });
});
");

?>

<?php $form = ActiveForm::begin([
    'id' => 'part-sell-form',
    'validateOnChange' => false,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-sell-form']),
    'options' => [
        'autocomplete' => 'off',
    ],
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
        <?= $form->field($model, 'time')->widget(DateTimePicker::class, [
            'clientOptions' => [
                'todayBtn' => true,
            ],
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'currency')->dropDownList($currencyOptions) ?>
    </div>
    <div class="col-md-12">
        <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
    </div>
    <div class="col-md-12" style="margin-bottom: 2rem">
        <div id="bill-exists-button">
            <?= Html::button(Yii::t('hipanel:stock', 'The bill exists'), ['class' => 'btn btn-default']) ?>
        </div>
        <div id="bill-exists-field" style="display: none">
            <?= $form->field($model, 'bill_id')->widget(BillCombo::class, [
                'pluginOptions' => [
                    'select2Options' => [
                        'allowClear' => false,
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>

<div class="well well-sm parts-for-sell">
    <legend><?= Yii::t('hipanel:stock', 'Parts') ?></legend>
    <?php $byType = []; ?>
    <?php $parts = PartSort::byGeneralRules()->values($parts); ?>
    <?php foreach ($parts as $part) : ?>
        <?php $byType[$part->model->type_label][] = $part ?>
    <?php endforeach; ?>

    <?php foreach ($byType as $type => $typeParts): ?>
        <h3><?= $type ?></h3>
        <?php foreach (array_chunk($typeParts, 2) as $row): ?>
            <div class="row">
                <?php foreach ($row as $part) : ?>
                    <div class="col-md-6">
                        <?= Html::activeHiddenInput($model, "ids[]", ['value' => $part->id]) ?>
                        <?= $form->field($model, "sums[$part->id]")->textInput([
                            'placeholder' => Yii::t('hipanel:stock', 'Part price'),
                        ])->label(sprintf(
                            '%s @ %s',
                            Html::a($part->title, ['@part/view', 'id' => $part->id], ['tabindex' => -1]),
                            $part->dst_name
                        )); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>

</div>

<div class="row">
    <div class="col-xs-6 col-sm-8">
        <?= Html::submitButton(Yii::t('hipanel:stock', 'Sell'), ['class' => 'btn btn-success']) ?> &nbsp;
        <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
    </div>
    <div class="col-xs-6 col-sm-4 part-sell-total-container">
        <div class="well well-sm text-center">
            <?= Yii::t('hipanel:stock', 'Total:') ?> <span id="part-sell-total">0</span>
        </div>
    </div>
</div>

<?php $form::end() ?>
