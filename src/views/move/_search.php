<?php

use hipanel\modules\stock\widgets\combo\DestinationCombo;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hipanel\modules\stock\widgets\combo\SourceCombo;
use hiqdev\combo\StaticCombo;
/**
 * @var \hipanel\widgets\AdvancedSearch $search
 */
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
    <?= $search->field('src_name_like')->widget(SourceCombo::classname()) ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('dst_name_like')->widget(DestinationCombo::classname()) ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('serial_like') ?>
</div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('descr_like') ?>
</div>



