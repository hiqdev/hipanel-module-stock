<?php
use hipanel\modules\stock\widgets\combo\DestinationCombo;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hipanel\modules\stock\widgets\combo\SourceCombo;
use hiqdev\combo\StaticCombo;

?>
<div class="col-md-4">
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
<!-- /.col-md-4 -->
<div class="col-md-4">
    <?= $search->field('src_name_like')->widget(SourceCombo::classname()) ?>
    <?= $search->field('dst_name_like')->widget(DestinationCombo::classname()) ?>
</div>
<!-- /.col-md-4 -->
<div class="col-md-4">
    <?= $search->field('serial_like') ?>
    <?= $search->field('descr_like') ?>
</div>
<!-- /.col-md-4 -->



