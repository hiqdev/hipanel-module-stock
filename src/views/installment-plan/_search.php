<?php

/** @var \hipanel\widgets\AdvancedSearch $search */

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\client\widgets\combo\SellerCombo;
use hipanel\modules\finance\widgets\MonthRangePicker;
use hipanel\modules\stock\widgets\combo\InstallmentPlanStateCombo;

?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('seller_id')->widget(SellerCombo::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('client_id')->widget(ClientCombo::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('state')->widget(InstallmentPlanStateCombo::class) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= MonthRangePicker::widget([
        'model' => $search->model,
        'timeTillAttribute' => 'month',
        'timeFromAttribute' => 'month',
    ]) ?>
</div>
