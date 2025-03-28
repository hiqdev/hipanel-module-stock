<?php

use hipanel\modules\stock\widgets\combo\CompanyCombo;
use hipanel\modules\stock\widgets\combo\ContactCombo;
use hipanel\widgets\RefCombo;
use hiqdev\yii2\daterangepicker\DateRangePicker;

/**
 * @var \hipanel\widgets\AdvancedSearch $search
 * @var \yii\web\View $this
 * @var \hipanel\models\IndexPageUiOptions $uiModel
 */

?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('type')->widget(RefCombo::class, [
        'gtype' => 'type,zorder',
        'multiple' => false,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('state')->widget(RefCombo::class, [
        'gtype' => 'state,zorder',
        'multiple' => false,
    ]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('seller_id')->widget(ContactCombo::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('buyer_id')->widget(ContactCombo::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('no_ilike') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('company_id')->widget(CompanyCombo::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('name_ilike') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <div class="form-group">
        <?= DateRangePicker::widget([
            'model' => $search->model,
            'attribute' => 'time_from',
            'attribute2' => 'time_till',
            'options' => [
                'class' => 'form-control',
                'placeholder' => Yii::t('hipanel', 'Time'),
            ],
            'dateFormat' => 'yyyy-mm-dd',
        ]) ?>
    </div>
</div>

<?php if ($uiModel->representation === 'profit-report'): ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="form-group">
            <?= DateRangePicker::widget([
                'model' => $search->model,
                'attribute' => 'profit_time_from',
                'attribute2' => 'profit_time_till',
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => Yii::t('hipanel:stock', 'Profit period'),
                ],
                'dateFormat' => 'yyyy-mm-dd',
            ]) ?>
        </div>
    </div>
<?php endif ?>
