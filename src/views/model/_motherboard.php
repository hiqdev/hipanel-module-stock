<?php
use yii\helpers\Html;

?>

<div class="form-group">
    <label for="exampleInputEmail1"><?= Html::getAttributeName('RAM_AMOUNT')?></label>
    <?= Html::activeTextInput($model, "[$i][props]RAM_AMOUNT", ['class' => 'form-control']); ?>
</div>

<div class="form-group">
    <label for="exampleInputEmail1"><?= Html::getAttributeName('RAM_QTY')?></label>
    <?= Html::activeTextInput($model, "[$i][props]RAM_QTY", ['class' => 'form-control']); ?>
</div>

<div class="form-group">
    <label for="exampleInputEmail1"><?= Html::getAttributeName('CPU_QTY')?></label>
    <?= Html::activeTextInput($model, "[$i][props]CPU_QTY", ['class' => 'form-control']); ?>
</div>
