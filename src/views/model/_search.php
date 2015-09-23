<?php
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hiqdev\assets\icheck\iCheckAsset;
use hiqdev\combo\StaticCombo;

iCheckAsset::register($this);

$this->registerJs("jQuery('.field-modelsearch-show_hidden_from_user input[type=checkbox]').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_flat',
    increaseArea: '20%' // optional
});");

$this->registerCss('.field-modelsearch-show_hidden_from_user { margin-top: 45px; }');
?>

<div class="col-md-3">
    <?= $search->field('partno_like')->widget(PartnoCombo::classname()) ?>
    <?= $search->field('type')->widget(StaticCombo::classname(), [
        'data' => $types,
        'hasId' => true,
        'pluginOptions' => [
            'select2Options' => [
                'multiple' => false,
            ],
        ],
    ]) ?>
</div>
<!-- /.col-md-3 -->
<div class="col-md-3">
    <?= $search->field('brand')->widget(StaticCombo::classname(), [
        'data' => $brands,
        'hasId' => true,
        'pluginOptions' => [
            'select2Options' => [
                'multiple' => false,
            ],
        ],
    ]) ?>
    <?= $search->field('model_like') ?>
</div>
<!-- /.col-md-3 -->
<div class="col-md-3">
    <?= $search->field('short_like') ?>
    <?= $search->field('descr_like') ?>
</div>
<!-- /.col-md-3 -->
<div class="col-md-3">
    <?= $search->field('group_like') ?>
    <?php /* = $search->field('show_hidden_from_user')->widget(StaticCombo::classname(), [
        'data' => [
            '0' => Yii::t('app', 'Hide hidden'),
            '1' => Yii::t('app', 'Show hidden'),
        ],
        'hasId' => true,
        'pluginOptions' => [
            'select2Options' => [
                'multiple' => false,
            ],
        ],
    ]) */ ?>

    <?= $search->field('show_hidden_from_user')->checkbox(); ?>
</div>
<!-- /.col-md-3 -->
