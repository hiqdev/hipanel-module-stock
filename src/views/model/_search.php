<?php
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hiqdev\assets\icheck\iCheckAsset;
use hiqdev\combo\StaticCombo;
/**
 * @var \hipanel\widgets\AdvancedSearch $search
 */
iCheckAsset::register($this);

$this->registerJs("jQuery('.field-modelsearch-show_hidden_from_user input[type=checkbox]').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_flat',
    increaseArea: '20%' // optional
});");

?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('partno_like')->widget(PartnoCombo::classname()) ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
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
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('brand')->widget(StaticCombo::classname(), [
        'data' => $brands,
        'hasId' => true,
        'pluginOptions' => [
            'select2Options' => [
                'multiple' => false,
            ],
        ],
    ]) ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('model_like') ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('short_like') ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('descr_like') ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('group_like') ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('show_hidden_from_user')->checkbox(); ?>
</div>
