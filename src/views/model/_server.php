<?php
use yii\helpers\Html;

?>

<div class="form-group">
    <label for="exampleInputEmail1"><?= Html::getAttributeName('units_qty')?></label>
    <?= Html::activeTextInput($model, "[$i][props]units_qty", ['class' => 'form-control']); ?>
</div>

<div class="form-group">
    <label for="exampleInputEmail1"><?= Html::getAttributeName('35_hdd_qty')?></label>
    <?= Html::activeTextInput($model, "[$i][props]35_hdd_qty", ['class' => 'form-control']); ?>
</div>

<div class="form-group">
    <label for="exampleInputEmail1"><?= Html::getAttributeName('25_hdd_qty')?></label>
    <?= Html::activeTextInput($model, "[$i][props]25_hdd_qty", ['class' => 'form-control']); ?>
</div>

<div class="form-group">
    <label for="exampleInputEmail1"><?= Html::getAttributeName('ram_qty')?></label>
    <?= Html::activeTextInput($model, "[$i][props]ram_qty", ['class' => 'form-control']); ?>
</div>

<div class="form-group">
    <label for="exampleInputEmail1"><?= Html::getAttributeName('cpu_qty')?></label>
    <?= Html::activeTextInput($model, "[$i][props]cpu_qty", ['class' => 'form-control']); ?>
</div>
