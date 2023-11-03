<?php

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\stock\forms\PartSellForm;
use hipanel\modules\stock\models\Part;
use hipanel\modules\stock\widgets\combo\ContactCombo;
use hipanel\modules\finance\widgets\combo\BillHwPurchaseCombo;
use hipanel\widgets\DateTimePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var Part[] $partsByModelType
 * @var array $partModels
 * @var array $currencyOptions
 * @var PartSellForm $model
 */

$this->registerCss('
select[readonly].select2-hidden-accessible + .select2-container {
    pointer-events: none;
    touch-action: none;
}
select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
    background: #eee;
    box-shadow: none;
}

select[readonly].select2-hidden-accessible + .select2-container .select2-selection__arrow,
select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
    display: none;
}
');

$this->registerJs(/** @lang ECMAScript 6 */ <<<JS
function setContactFieldByClientName(selectedClientId, selectedClientName) {
    $.post('/client/contact/search', {return: ['id', 'name', 'email'], select: 'min', client: selectedClientName}).done(function (contacts) {
        let autoContact = contacts.filter(contact => contact.id === selectedClientId);    
        if (autoContact.length > 0) {
            $('#partsellform-contact_id')
                .empty()
                .append('<option value="' + autoContact[0].id + '">'+ autoContact[0].name + '</option>')
                .val(autoContact[0]['id'])
                .trigger('change');
        } else {
            $('#partsellform-contact_id').empty();
        }
    });
}
// Auto select contact when client selected
$('#partsellform-client_id').on('select2:select', function (e) {
    let clientInput = $('#partsellform-client_id option:selected');
    let selectedClientId = clientInput.val();
    let selectedClientName = clientInput.text().trim();
    setContactFieldByClientName(selectedClientId, selectedClientName);
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
            total.html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
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
$('#partsellform-bill_id').on('select2:select', function () {
    let billInput = $('#partsellform-bill_id option:selected');
    let selectedBillId = billInput.val();
    $('#partsellform-currency option').attr('disabled', false);
    $.post('/finance/bill/index', {return: ['id', 'time', 'currency', 'client_id', 'client'], select: 'min', id: selectedBillId}).done(function (bills) {
        let auto = bills.filter(bill => bill.id.toString() === selectedBillId.toString());
        if (auto.length > 0) {
            $('#partsellform-time').val(auto[0].time).attr({readonly: true}).parent().datetimepicker('remove');
            $('#partsellform-currency').val(auto[0].currency).attr('readonly', true);
            $('#partsellform-currency option:not(:selected)').attr('disabled', true);
            setContactFieldByClientName(auto[0].client_id, auto[0].client);
            $('#part-sell-fields').hide();
            $('#part-sell-message').show();
        }
    });
});
// Set parts prcie by model
document.querySelectorAll("#set-price-by-model input").forEach((modelInput) => {
  modelInput.addEventListener("keyup", function (event) {
    const price = event.target.value;
    const modelId = event.target.id;
    let lastInput = null;
    [].forEach.call(document.querySelectorAll(`input[data-model-id*='\${modelId}']`), input => {
      if (input.matches("[id*='partsellform-sums']")) {
        input.value = price;
        lastInput = input;
      }
    });
    if (lastInput) {
      lastInput.dispatchEvent(new Event("change"));
    }
  });
});

JS
);

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

<div id="part-sell-fields" class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'client_id')->widget(ClientCombo::class, [
            'pluginOptions' => [
                'select2Options' => [
                    'dropdownParent' => new JsExpression('$(".modal.in")'),
                ],
            ],
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'contact_id')->widget(ContactCombo::class, [
            'pluginOptions' => [
                'select2Options' => [
                    'dropdownParent' => new JsExpression('$(".modal.in")'),
                ],
            ],
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'time')->widget(DateTimePicker::class, [
            'clientOptions' => [
                'endDate' => date('Y-m-d'),
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
</div>
<div id="part-sell-message" class="row" style="display: none;">
    <div class="col-md-12">
        <p class="bg-warning text-center" style="padding: 1rem 2rem">
            <?= Yii::t('hipanel:stock',
                'All data about the bill already exist. You do not need to fill out the form.') ?>
        </p>
    </div>
</div>
<div class="row">
    <div class="col-md-12" style="margin-bottom: 2rem">
        <div id="bill-exists-button">
            <?= Html::button(Yii::t('hipanel:stock', 'The bill exists'), ['class' => 'btn btn-warning']) ?>
        </div>
        <div id="bill-exists-field" style="display: none">
            <?= $form->field($model, 'bill_id')->widget(BillHwPurchaseCombo::class, [
                'pluginOptions' => [
                    'select2Options' => [
                        'allowClear' => false,
                        'dropdownParent' => new JsExpression('$(".modal.in")'),
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>

<div id="set-price-by-model" class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('hipanel:stock', 'Setting the price for all parts of the listed models') ?>
    </div>
    <ul class="list-group">
        <?php foreach (array_chunk($partModels, 2, true) as $row) : ?>
            <li class="list-group-item">
                <div class="row">
                    <?php foreach ($row as $modelId => $modelLabel) : ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="<?= $modelId ?>"><?= $modelLabel ?></label>
                                <input type="number" step="0.01" class="form-control" id="<?= $modelId ?>"
                                       placeholder="<?= Yii::t('hipanel:stock',
                                           'A price for all parts of this model') ?>">
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </li>
        <?php endforeach ?>
    </ul>
</div>

<div class="panel panel-default parts-for-sell">

    <div class="panel-heading"><?= Yii::t('hipanel:stock', 'Parts') ?></div>

    <?php foreach ($partsByModelType as $modelType => $typeParts): ?>
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
                            <?= $form->field($model, "sums[$part->id]")->textInput([
                                'placeholder' => Yii::t('hipanel:stock', 'Part price'),
                                'data' => ['model-id' => $part->model_id],
                            ])->label(sprintf(
                                '%s @ %s',
                                Html::a($part->title,
                                    ['@part/view', 'id' => $part->id],
                                    [
                                        'tabindex' => -1,
                                        'target' => '_blank',
                                    ]),
                                $part->dst_name
                            )) ?>
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
    <div class="col-xs-6 col-sm-4 part-sell-total-container">
        <div class="description-block border-right">
            <h5 class="description-header"><span id="part-sell-total">0</span></h5>
            <span class="description-text">TOTAL</span>
        </div>
    </div>
</div>

<?php $form::end() ?>
