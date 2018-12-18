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
    <?= $search->field('partno_inilike')->widget(PartnoCombo::class, ['multiple' => true, 'primaryFilter' => 'partno_inilike']) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('type')->widget(StaticCombo::class, [
        'data' => $types,
        'hasId' => true,
        'multiple' => false,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('src_name_like')->widget(SourceCombo::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('dst_name_like')->widget(DestinationCombo::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('serial_like') ?></div>
<div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('descr_like') ?></div>
<div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('order_no_ilike') ?></div>
