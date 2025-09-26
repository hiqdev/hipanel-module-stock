<?php

use hipanel\modules\stock\widgets\combo\DestinationCombo;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hipanel\modules\stock\widgets\combo\SourceCombo;
use hipanel\modules\stock\widgets\combo\WithDeletedSourceCombo;
use hipanel\widgets\AdvancedSearch;
use hiqdev\combo\StaticCombo;
use hiqdev\yii2\daterangepicker\DateRangePicker;

/**
 * @var AdvancedSearch $search
 * @var array $types
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

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('src_or_dst')->widget(WithDeletedSourceCombo::class, ['hasId' => true]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="form-group">
        <?= DateRangePicker::widget([
            'model' => $search->model,
            'attribute' => 'time_from',
            'attribute2' => 'time_till',
            'options' => [
                'class' => 'form-control',
                'placeholder' => Yii::t('hipanel', 'Date'),
            ],
            'dateFormat' => 'yyyy-MM-dd',
        ]) ?>
    </div>
</div>

<div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('serial_like') ?></div>
<div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('descr_like') ?></div>
<div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('first_move_ilike') ?></div>
