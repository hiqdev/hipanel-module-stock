<?php
use yii\helpers\Html;

?>

<div class="form-group">
    <label><?= Html::activeLabel($model, 'RAM_AMOUNT')?></label>
    <?= Html::activeTextInput($model, "[$i][props]RAM_AMOUNT", ['class' => 'form-control']); ?>
</div>

<div class="form-group">
    <label><?= Html::activeLabel($model, 'RAM_QTY')?></label>
    <?= Html::activeInput('number', $model, "[$i][props]RAM_QTY", ['class' => 'form-control', 'min' => 1, 'max' => 100]); ?>
</div>

<div class="form-group">
    <label><?= Html::activeLabel($model, 'CPU_QTY')?></label>
    <?= Html::activeTextInput($model, "[$i][props]CPU_QTY", ['class' => 'form-control']); ?>
</div>
