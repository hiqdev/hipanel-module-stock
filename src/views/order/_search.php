<?php

use hipanel\modules\stock\widgets\combo\ContactCombo;
use hipanel\widgets\DatePicker;
use hipanel\widgets\RefCombo;
use yii\bootstrap\Html;

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
    <?= $search->field('name_ilike') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="form-group">
        <?= Html::tag('label', Yii::t('hipanel', 'Time'), ['class' => 'control-label']); ?>
        <?= DatePicker::widget([
            'id' => 'time_date-picker',
            'model' => $search->model,
            'attribute' => 'time',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
            ],
        ]) ?>
    </div>
</div>
