<?php

use yii\helpers\Html;
use hipanel\modules\stock\models\Model;

/** @var Model $model */
/** @var int $i */
?>

<div class="form-group">
    <label class="control-label"><?= $model->getAttributeLabel('formfactor') ?></label>
    <?= Html::activeTextInput($model, "[$i]props[formfactor]", ['class' => 'form-control', 'value' => $model->getModelProp('formfactor')]) ?>
</div>
