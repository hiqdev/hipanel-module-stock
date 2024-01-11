<?php

use hipanel\modules\stock\models\HardwareSettings;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var HardwareSettings $model */

?>

<?php $form = ActiveForm::begin([
    'id' => 'form',
    'validateOnChange' => true,
    'enableAjaxValidation' => false,
    'options' => [
        'autocomplete' => 'off',
    ],
]) ?>

<?= Html::activeHiddenInput($model, 'id') ?>
<?= Html::activeHiddenInput($model, 'model_type') ?>

<?php foreach ($model->props as $type => $settings): ?>
    <?php if (!empty($settings)) : ?>
        <h5 style="margin-bottom: 1em; padding-bottom: .5em; border-bottom: 2px solid #CCCCCC; font-weight: bold;"><?= mb_strtoupper($type) ?></h5>
        <?php foreach (array_chunk($settings, 2, true) as $attributes): ?>
            <div class="row">
                <?php foreach ($attributes as $attribute => $value) : ?>
                    <?php
                    if ($attribute === 'id') {
                        continue;
                    }
                    ?>
                    <div class="col-md-6">
                        <?= $this->context->field($form, $type, $attribute) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif ?>
<?php endforeach; ?>

<?= Html::submitButton(Yii::t('hipanel:stock', 'Save'), ['class' => 'btn btn-success']) ?> &nbsp;
<?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) ?>


<?php ActiveForm::end() ?>
