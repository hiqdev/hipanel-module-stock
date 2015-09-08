<?php
use hipanel\modules\stock\widgets\combo\DestinationCombo;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hipanel\modules\stock\widgets\combo\SourceCombo;
use hiqdev\combo\StaticCombo;

?>

<div class="col-md-4">
    <?= $search->field('partno_like')->widget(PartnoCombo::classname()) ?>
    <?= $search->field('model_types')->widget(StaticCombo::classname(), [
        'data'  => $types,
        'hasId' => true,
        'pluginOptions' => [
            'select2Options' => [
                'multiple' => false,
            ],
        ],
    ]) ?>
    <?= $search->field('model_brands')->widget(StaticCombo::classname(), [
        'data'  => $brands,
        'hasId' => true,
        'pluginOptions' => [
            'select2Options' => [
                'multiple' => false,
            ],
        ],
    ]) ?>
</div>

<div class="col-md-4">
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
</div>

<div class="col-md-4">
    <?= $search->field('serial_like') ?>
    <?= $search->field('move_descr_like') ?>
    <?= $search->field('order_data_like') ?>
</div>