<?php

use hipanel\modules\stock\widgets\combo\DestinationCombo;
use hipanel\modules\stock\widgets\combo\PartCombo;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hipanel\modules\stock\widgets\combo\SourceCombo;
use hiqdev\combo\StaticCombo;
use hipanel\widgets\RefCombo;
use hipanel\widgets\DatePicker;
use hiqdev\yii2\daterangepicker\DateRangePicker;
use yii\helpers\Html;

/**
 * @var \hipanel\widgets\AdvancedSearch $search
 */
?>


<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('partno_inilike')->widget(PartnoCombo::class, [
        'multiple' => true, 'primaryFilter' => 'partno_inilike',
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('model_types')->widget(RefCombo::class, [
        'gtype' => 'type,model',
        'multiple' => true,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('state')->widget(RefCombo::class, [
        'gtype' => 'state,part',
        'multiple' => false,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('model_brands')->widget(RefCombo::class, [
        'gtype' => 'type,brand',
        'multiple' => true,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('serial_like') ?></div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('id_in')->widget(PartCombo::class, [
        'hasId' => true,
        'multiple' => true,
        'current' => array_combine((array)$search->model->id_in, (array)$search->model->id_in),
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('move_descr_ilike') ?></div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('src_name_like')->widget(SourceCombo::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('dst_name_like')->widget(DestinationCombo::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('order_no_ilike') ?></div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('company_id')->dropDownList($search->model->companies, ['prompt' => Yii::t('hipanel:stock', 'Company')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('place_in')->widget(StaticCombo::class, [
        'data' => $locations,
        'hasId' => true,
        'multiple' => true,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('currency')->widget(StaticCombo::class, [
        'data' => ['usd' => 'USD', 'eur' => 'EUR'],
        'hasId' => true,
        'multiple' => false,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('limit') ?></div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="form-group">
        <?= Html::tag('label', Yii::t('hipanel:stock', 'Last move date'), ['class' => 'control-label']); ?>
        <?= DatePicker::widget([
            'id' => 'move_time_date-picker',
            'model' => $search->model,
            'attribute' => 'move_time',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
            ],
        ]) ?>
    </div>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="form-group">
        <?= Html::tag('label', Yii::t('hipanel:stock', 'Created range'), ['class' => 'control-label']) ?>
        <?= DateRangePicker::widget([
            'id' => 'create_time-date-range-picker',
            'model' => $search->model,
            'attribute' => 'create_time_from',
            'attribute2' => 'create_time_till',
            'options' => [
                'class' => 'form-control',
            ],
            'dateFormat' => 'yyyy-MM-dd',
        ]) ?>
    </div>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('buyer_in')->widget(\hipanel\modules\client\widgets\combo\ClientCombo::class, [
        'multiple' => true,
    ]) ?>
</div>
