<?php

use hipanel\models\IndexPageUiOptions;
use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\server\widgets\combo\HubCombo;
use hipanel\modules\stock\widgets\combo\CompanyCombo;
use hipanel\modules\stock\widgets\combo\LocationsCombo;
use hipanel\modules\stock\widgets\combo\OrderCombo;
use hipanel\modules\stock\widgets\combo\PartCombo;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hipanel\modules\stock\widgets\StockLocationsListTreeSelect;
use hipanel\widgets\AdvancedSearch;
use hiqdev\combo\StaticCombo;
use hipanel\widgets\RefCombo;
use hipanel\widgets\DateTimePicker;
use hiqdev\yii2\daterangepicker\DateRangePicker;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var IndexPageUiOptions $uiModel
 * @var AdvancedSearch $search
 */

$this->registerJs(<<<"JS"
(() => {
  $("#{$search->getForm()->getId()}").on("afterValidate", function () {
    $(this).data("yiiActiveForm").validated = true;
  });
})();
JS
)

?>


<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('partno_in')->widget(PartnoCombo::class, [
        'multiple' => true,
        'primaryFilter' => 'partno_leftlikei',
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

<div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('serial_ilike') ?></div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('id_in')->widget(PartCombo::class, [
        'hasId' => true,
        'multiple' => true,
        'current' => array_combine((array)$search->model->id_in, (array)$search->model->id_in),
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('move_descr_ilike') ?></div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('src_name_in')->textarea() ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('dst_name_in')->textarea() ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('rack_in')->widget(HubCombo::class, [
        'multiple' => true,
        'hubType' => 'rack',
    ]) ?>
</div>

<?php if (Yii::$app->user->can('sale.create')): ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('company_id')->widget(CompanyCombo::class) ?>
    </div>
<?php endif ?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('device_location_like') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('place_in')->widget(LocationsCombo::class, ['multiple' => true]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('stock_location_in')->widget(StockLocationsListTreeSelect::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('currency')->widget(StaticCombo::class, [
        'data' => ['usd' => 'USD', 'eur' => 'EUR'],
        'hasId' => true,
        'multiple' => false,
    ]) ?>
</div>

<?php if (Yii::$app->user->can('part.create')): ?>
    <div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('limit') ?></div>
<?php endif ?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="form-group">
        <?= DateTimePicker::widget([
            'id' => 'move_time_date-picker',
            'model' => $search->model,
            'attribute' => 'move_time',
            'clientOptions' => [
                'autoclose' => true,
                'minView' => 2,
                'format' => 'yyyy-mm-dd',
            ],
            'options' => [
                'placeholder' => Yii::t('hipanel:stock', 'Last move date'),
            ],
        ]) ?>
    </div>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="form-group">
        <?= DateRangePicker::widget([
            'model' => $search->model,
            'attribute' => 'create_time_from',
            'attribute2' => 'create_time_till',
            'options' => [
                'id' => 'create_time-date-range-picker',
                'class' => 'form-control',
                'placeholder' => Yii::t('hipanel:stock', 'Created range'),
            ],
            'dateFormat' => 'yyyy-MM-dd',
        ]) ?>
    </div>
</div>

<?php if (Yii::$app->user->can('order.read')): ?>
    <div class="col-md-4 col-sm-6 col-xs-12"><?= $search->field('reserve_ilike') ?></div>
<?php endif ?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('buyer_in')->widget(ClientCombo::class, [
        'multiple' => true,
    ]) ?>
</div>

<?php if (Yii::$app->user->can('order.read')): ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('order_id')->widget(OrderCombo::class) ?>
    </div>

    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('order_name_ilike') ?>
    </div>
<?php endif ?>

<?php if (Yii::$app->user->can('move.read')): ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('first_move_ilike') ?>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('last_move_ilike') ?>
    </div>
<?php endif ?>

<?php if ($uiModel->representation === 'profit-report'): ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="form-group">
            <?= Html::tag('label', Yii::t('hipanel:stock', 'Profit period'), ['class' => 'control-label']); ?>
            <?= DateRangePicker::widget([
                'model' => $search->model,
                'attribute' => 'profit_time_from',
                'attribute2' => 'profit_time_till',
                'options' => [
                    'class' => 'form-control',
                ],
                'dateFormat' => 'yyyy-mm-dd',
            ]) ?>
        </div>
    </div>
<?php endif ?>
