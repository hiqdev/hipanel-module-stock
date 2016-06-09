<?php

use hipanel\modules\stock\widgets\combo\DestinationCombo;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hipanel\modules\stock\widgets\combo\SourceCombo;
use hiqdev\combo\StaticCombo;
use hipanel\widgets\RefCombo;
use kartik\widgets\DatePicker;
use yii\helpers\Html;

/**
 * @var \hipanel\widgets\AdvancedSearch $search
 */
?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('partno_like')->widget(PartnoCombo::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('model_types')->widget(RefCombo::class, [
        'gtype'    => 'type,model',
        'multiple' => true,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('model_brands')->widget(RefCombo::class, [
        'gtype'    => 'type,brand',
        'multiple' => true,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="form-group">
        <?= Html::tag('label', 'Created range', ['class' => 'control-label']); ?>
        <?= DatePicker::widget([
            'model'         => $search->model,
            'type'          => DatePicker::TYPE_RANGE,
            'attribute'     => 'create_time_from',
            'attribute2'    => 'create_time_till',
            'pluginOptions' => [
                'autoclose' => true,
                'format'    => 'yyyy-mm-dd',
            ],
        ]) ?>
    </div>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('place')->widget(StaticCombo::class, [
        'data'     => $locations,
        'hasId'    => true,
        'multiple' => true,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('src_name_like')->widget(SourceCombo::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('dst_name_like')->widget(DestinationCombo::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('currency')->widget(StaticCombo::class, [
        'data' => ['usd' => 'USD', 'eur' => 'EUR'],
        'hasId' => true,
        'pluginOptions' => [
            'select2Options' => [
                'multiple' => false,
            ],
        ],
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('serial_like') ?></div>
<div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('move_descr_like') ?></div>
<div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('order_data_like') ?></div>
