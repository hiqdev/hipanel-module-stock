<?php

use hipanel\helpers\Url;
use hipanel\modules\stock\models\Part;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\bootstrap\Html;

/**
 * @var Part $model
 * @var Part[] $parts
 * @var int $sownCount
 */

?>

<?php $form = ActiveForm::begin([
    'id' => 'part-sell-by-plan-form',
    'action' => Url::toRoute(['@part/set-real-serials']),
    'validateOnChange' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['@part/set-real-serials']),
    'options' => [
        'autocomplete' => 'off',
    ],
]) ?>

<?php if ($sownCount > 0) : ?>
    <?= Alert::widget([
        'options' => ['class' => 'alert-danger'],
        'body' => Yii::t('hipanel:stock', '{0, plural, one{# part} few{# parts} other{# parts}} were discarded because they already have a real serial number or changes are not available (RMA, Trash)', $sownCount),
    ]) ?>
<?php endif ?>

<?= $form->field($model, 'serials')->textarea(['rows' => 5])->hint(Yii::t('hipanel:stock', 'Serials delimited with a space, comma or a new line')) ?>

<div class="parts-for-sell panel panel-default">
    <?= Html::tag('div', Yii::t('hipanel:stock', 'Parts') . sprintf('<span class="badge pull-right">%s</span>', count($parts)), ['class' => 'panel-heading']) ?>
    <table class="table table-striped table-condensed">
        <?php foreach (array_chunk($parts, 3) as $pair): ?>
            <tr>
                <?php foreach ($pair as $part) : ?>
                    <td style="width: 33%">
                        <?= Html::activeHiddenInput($model, "ids[]", ['value' => $part->id]) ?>
                        <?= Html::a($part->title, ['@part/view', 'id' => $part->id], ['tabindex' => -1]) ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="row">
    <div class="col-xs-6 col-sm-8">
        <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?> &nbsp;
        <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>
    </div>
</div>

<?php $form::end() ?>
