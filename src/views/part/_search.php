<?php

use hipanel\modules\stock\widgets\combo\DestinationCombo;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hipanel\modules\stock\widgets\combo\SourceCombo;
use hiqdev\combo\StaticCombo;
use kartik\widgets\DatePicker;
use yii\helpers\Html;

?>
<div class="col-lg-6 col-md-12">
    <?= $search->field('partno_like')->widget(PartnoCombo::classname()) ?>
</div>
<div class="col-lg-6 col-md-12">
    <?= $search->field('model_types')->widget(StaticCombo::classname(), [
        'data'  => $types,
        'hasId' => true,
        'pluginOptions' => [
            'select2Options' => [
                'multiple' => false,
            ],
        ],
    ]) ?>
</div>
<div class="col-md-12">

    <?= $search->field('model_brands')->widget(StaticCombo::classname(), [
        'data'  => $brands,
        'hasId' => true,
        'pluginOptions' => [
            'select2Options' => [
                'multiple' => false,
            ],
        ],
    ]) ?>
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

<div class="col-md-12">
    <?= $search->field('place')->widget(StaticCombo::classname(), [
        'data'  => $locations,
        'hasId' => true,
        'pluginOptions' => [
            'select2Options' => [
                'multiple' => false,
            ],
        ],
    ]) ?>
    <?= $search->field('src_name_like')->widget(SourceCombo::classname()) ?>
    <?= $search->field('dst_name_like')->widget(DestinationCombo::classname()) ?>
    <?= $search->field('currency')->widget(StaticCombo::classname(), [
        'data' => ['usd' => 'USD', 'eur' => 'EUR'],
        'hasId' => true,
        'pluginOptions' => [
            'select2Options' => [
                'multiple' => false,
            ],
        ],
    ]) ?>
</div>

<div class="col-md-12">
    <?= $search->field('serial_like') ?>
    <?= $search->field('move_descr_like') ?>
    <?= $search->field('order_data_like') ?>
</div>
