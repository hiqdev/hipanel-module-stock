<?php

use yii\helpers\Html;
use hipanel\modules\stock\models\Model;

/** @var Model $model */
/** @var int $i */
?>

<div class="form-group">
    <label class="control-label"><?= $model->getAttributeLabel('max_ram_size') ?></label>
    <?= Html::activeTextInput($model, "[$i]props[max_ram_size]", ['class' => 'form-control', 'value' => $model->getModelProp('max_ram_size')]) ?>
</div>

<div class="form-group">
    <label class="control-label"><?= $model->getAttributeLabel('ram_slots') ?></label>
    <?= Html::activeInput('number', $model, "[$i]props[ram_slots]", ['class' => 'form-control', 'min' => 1, 'max' => 100, 'value' => $model->getModelProp('ram_slots')]) ?>
</div>

<div class="form-group">
    <label class="control-label"><?= $model->getAttributeLabel('cpu_sockets') ?></label>
    <?= Html::activeTextInput($model, "[$i]props[cpu_sockets]", ['class' => 'form-control', 'value' => $model->getModelProp('cpu_sockets')]) ?>
</div>
