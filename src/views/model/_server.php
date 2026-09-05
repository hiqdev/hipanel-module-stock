<?php

use yii\helpers\Html;
use hipanel\modules\stock\models\Model;

/** @var Model $model */
/** @var int $i */
?>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label"><?= $model->getAttributeLabel('units_qty') ?></label>
            <?= Html::activeInput('number', $model, "[$i]props[units_qty]", ['class' => 'form-control', 'min' => '1', 'max' => '100', 'value' => $model->getModelProp('units_qty')]) ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label"><?= $model->getAttributeLabel('25_hdd_qty') ?></label>
            <?= Html::activeTextInput($model, "[$i]props[25_hdd_qty]", ['class' => 'form-control', 'value' => $model->getModelProp('25_hdd_qty')]) ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label"><?= $model->getAttributeLabel('35_hdd_qty') ?></label>
            <?= Html::activeTextInput($model, "[$i]props[35_hdd_qty]", ['class' => 'form-control', 'value' => $model->getModelProp('35_hdd_qty')]) ?>
        </div>
    </div>
</div>
