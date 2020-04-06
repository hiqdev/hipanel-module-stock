<?php

use hipanel\modules\finance\models\Plan;
use hipanel\modules\finance\widgets\combo\PlanCombo;
use hipanel\modules\stock\forms\PartSellByPlanForm;
use yii\bootstrap\ActiveForm;
use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\stock\widgets\combo\ContactCombo;
use hipanel\widgets\DateTimePicker;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;

/** @var array $partsByModelType */
/** @var PartSellByPlanForm $model */

$this->registerJs(/** @lang ECMAScript 6 */<<<JS
function setContactFieldByClientName(selectedClientId, selectedClientName) {
    jQuery.post('/client/contact/search', {return: ['id', 'name', 'email'], select: 'min', client: selectedClientName}).done(function (contacts) {
        const autoContact = contacts.filter(contact => parseInt(contact.id) === parseInt(selectedClientId));
        if (autoContact.length > 0) {
            const contact = autoContact[0];
            const login = contact.name.length === 0 ? contact.email : contact.name;
            $('#partsellbyplanform-contact_id')
                .empty()
                .append('<option value="' + contact.id + '">'+ login + '</option>')
                .val(contact.id)
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
        <?= $form->field($model, 'client_id')->widget(ClientCombo::class, [
            'pluginOptions' => [
                'select2Options' => [
                    'dropdownParent' => new JsExpression('$(".modal.in")'),
                ],
            ],
        ]) ?>
        <?= $form->field($model, 'description')->textarea(['rows' => 5]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'contact_id')->widget(ContactCombo::class, [
            'pluginOptions' => [
                'select2Options' => [
                    'dropdownParent' => new JsExpression('$(".modal.in")'),
                ],
            ],
        ]) ?>
        <?= $form->field($model, 'plan_id')->widget(PlanCombo::class, [
            'tariffType' => [
                Plan::TYPE_HARDWARE,
            ],
            'pluginOptions' => [
                'select2Options' => [
                    'dropdownParent' => new JsExpression('$(".modal.in")'),
                ],
            ],
        ]) ?>
        <?= $form->field($model, 'time')->widget(DateTimePicker::class, ['clientOptions' => ['todayBtn' => true]]) ?>
    </div>
</div>

<div class="parts-for-sell panel panel-default">
    <?= Html::tag('div', Yii::t('hipanel:stock', 'Parts'), ['class' => 'panel-heading']) ?>
    <?php foreach ($partsByModelType as $modelType => $typeParts): ?>
        <table class="table">
            <thead>
            <tr>
                <th colspan="2"><?= mb_strtoupper($modelType) ?></th>
            </tr>
            </thead>
            <?php foreach (array_chunk($typeParts, 2) as $parts): ?>
                <tr>
                    <?php foreach ($parts as $part) : ?>
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
