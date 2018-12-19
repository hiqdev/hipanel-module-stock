<?php

use hiqdev\combo\StaticCombo;

$this->registerCss('label > .option-input { top: 6px; margin-right: .3rem; }'); // fix label display for checkbox

?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('type')->widget(StaticCombo::class, [
        'data' => $types,
        'hasId' => true,
        'multiple' => false,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('brand')->widget(StaticCombo::class, [
        'data' => $brands,
        'hasId' => true,
        'multiple' => false,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('state')->widget(StaticCombo::class, [
        'data' => $states,
        'hasId' => true,
        'multiple' => false,
    ]) ?>
</div>


<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('filter_like') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('model_like') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('descr_like') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('partno_like') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('group_like') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('hide_group_assigned')->checkbox(['class' => 'option-input']) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('show_hidden_from_user')->checkbox(['class' => 'option-input']) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('hide_unavailable')->checkbox(['class' => 'option-input']) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('show_deleted')->checkbox(['class' => 'option-input']) ?>
</div>

