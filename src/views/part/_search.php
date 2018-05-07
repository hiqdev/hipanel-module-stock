<?php

use hipanel\modules\stock\widgets\combo\DestinationCombo;
use hipanel\modules\stock\widgets\combo\PartCombo;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hipanel\modules\stock\widgets\combo\SourceCombo;
use hiqdev\combo\StaticCombo;
use hipanel\widgets\RefCombo;
use hipanel\widgets\DatePicker;
use yii\helpers\Html;

/**
 * @var \hipanel\widgets\AdvancedSearch $search
 */
?>


<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('partno_like')->widget(PartnoCombo::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('model_types')->widget(RefCombo::class, ['gtype' => 'type,model',
        'multiple' => true,]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('model_brands')->widget(RefCombo::class, ['gtype' => 'type,brand',
        'multiple' => true,]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="form-group">
        <?= Html::tag('label', Yii::t('hipanel:stock', 'Last move date'), ['class' => 'control-label']); ?>
        <?= DatePicker::widget(['model' => $search->model,
            'attribute' => 'move_time',
            'pluginOptions' => ['autoclose' => true,
                'format' => 'yyyy-mm-dd',],
        ]) ?>
    </div>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="form-group">
        <?= Html::tag('label', Yii::t('hipanel:stock', 'Created range'), ['class' => 'control-label']) ?>
        <?= DatePicker::widget(['model' => $search->model,
            'type' => DatePicker::TYPE_RANGE,
            'attribute' => 'create_time_from',
            'attribute2' => 'create_time_till',
            'pluginOptions' => ['autoclose' => true,
                'format' => 'yyyy-mm-dd',],
        ]) ?>
    </div>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('src_name_like')->widget(SourceCombo::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('dst_name_like')->widget(DestinationCombo::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('place_in')->widget(StaticCombo::class, ['data' => $locations,
        'hasId' => true,
        'multiple' => true,]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('currency')->widget(StaticCombo::class, ['data' => ['usd' => 'USD', 'eur' => 'EUR'],
        'hasId' => true,
        'multiple' => false,]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('serial_like') ?></div>
<div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('move_descr_like') ?></div>
<div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('order_no_ilike') ?></div>

<div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('limit') ?></div>
<div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('company_id')->dropDownList($search->model->companies, ['prompt' => Yii::t('hipanel:stock', 'Company')]) ?></div>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('id_in')->widget(PartCombo::class, ['multiple' => true]) ?>
</div>
