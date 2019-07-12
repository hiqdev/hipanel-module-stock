<?php

use hipanel\modules\finance\models\Plan;
use hipanel\modules\finance\widgets\combo\PlanCombo;
use yii\bootstrap\ActiveForm;
use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\stock\widgets\combo\ContactCombo;
use hipanel\modules\stock\helpers\PartSort;
use hipanel\widgets\DateTimePicker;
use yii\helpers\Url;
use yii\helpers\Html;

$this->registerJs(/** @lang ECMAScript 6 */
    <<<JS
function setContactFieldByClientName(selectedClientId, selectedClientName) {
    jQuery.post('/client/contact/search', {return: ['id', 'name', 'email'], select: 'min', client: selectedClientName}).done(function (contacts) {
        let autoContact = contacts.filter(contact => contact.id === selectedClientId);    
        if (autoContact.length > 0) {
            $('#partsellbyplanform-contact_id')
                .empty()
                .append('<option value="' + autoContact[0].id + '">'+ autoContact[0].name + '</option>')
                .val(autoContact[0]['id'])
                .trigger('change');
        } else {
            $('#partsellbyplanform-contact_id').empty();
        }
    });
}
// Auto select contact when client selected
$('#partsellbyplanform-client_id').on('select2:select', function (e) {
    let clientInput = $('#partsellbyplanform-client_id option:selected');
    let selectedClientId = clientInput.val();
    let selectedClientName = clientInput.text().trim();
    setContactFieldByClientName(selectedClientId, selectedClientName);
});
JS
);
?>
<?php $form = ActiveForm::begin([
    'id' => 'part-sell-by-plan-form',
    'validateOnChange' => false,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-sell-by-plan-form', 'scenario' => 'default']),
    'options' => [
        'autocomplete' => 'off',
    ],
]) ?>

<div id="part-sell-fields" class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'client_id')->widget(ClientCombo::class) ?>
        <?= $form->field($model, 'description')->textarea(['rows' => 5]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'contact_id')->widget(ContactCombo::class) ?>
        <?= $form->field($model, 'plan_id')->widget(PlanCombo::class, ['tariffType' => [Plan::TYPE_HARDWARE]]) ?>
        <?= $form->field($model, 'time')->widget(DateTimePicker::class, ['clientOptions' => ['todayBtn' => true]]) ?>
    </div>
</div>

<div class="parts-for-sell panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('hipanel:stock', 'Parts') ?>
    </div>
    <?php $byType = []; ?>
    <?php $parts = PartSort::byGeneralRules()->values($parts); ?>
    <?php foreach ($parts as $part) : ?>
        <?php $byType[$part->model_type_label][] = $part ?>
    <?php endforeach; ?>

    <?php foreach ($byType as $type => $typeParts): ?>
        <table class="table">
            <thead>
            <tr>
                <th colspan="2"><?= mb_strtoupper($type) ?></th>
            </tr>
            </thead>
            <?php foreach (array_chunk($typeParts, 2) as $row): ?>
                <tr>
                    <?php foreach ($row as $part) : ?>
                        <td style="width: 50%">
                            <?= Html::activeHiddenInput($model, "ids[]", ['value' => $part->id]) ?>
                            <?= sprintf('%s @ %s', Html::a($part->title, ['@part/view', 'id' => $part->id], ['tabindex' => -1]), $part->dst_name); ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endforeach; ?>
</div>

<div class="row">
    <div class="col-xs-6 col-sm-8">
        <?= Html::submitButton(Yii::t('hipanel:stock', 'Sell'), ['class' => 'btn btn-success']) ?> &nbsp;
        <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
    </div>
</div>

<?php $form::end() ?>
