<?php

use yii\helpers\Html;
use hipanel\modules\stock\models\Model;

/** @var Model $model */
/** @var int $i */
?>

<div class="form-group">
    <label class="control-label"><?= $model->getAttributeLabel('size') ?></label>
    <div class="input-group">
        <?= Html::activeTextInput($model, "[$i]props[size]", ['class' => 'form-control', 'value' => $model->getModelProp('size')]) ?>
        <span class="input-group-addon">GB</span>
    </div>
</div>
