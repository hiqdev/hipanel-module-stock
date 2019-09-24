<?php

use hipanel\modules\stock\widgets\combo\ContactCombo;
use hipanel\widgets\RefCombo;

/**
 * @var \hipanel\widgets\AdvancedSearch $search
 * @var \yii\web\View $this
 */

?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('type')->widget(RefCombo::class, [
        'gtype' => 'type,zorder',
        'multiple' => false,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('state')->widget(RefCombo::class, [
        'gtype' => 'state,zorder',
        'multiple' => false,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('seller_id')->widget(ContactCombo::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('buyer_id')->widget(ContactCombo::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('no_ilike') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('comment_ilike') ?>
</div>