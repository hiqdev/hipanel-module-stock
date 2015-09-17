<?php
use yii\helpers\Html;

?>

<div class="form-group">
    <label class="control-label"><?= Html::activeLabel($model, 'RAM_VOLUME')?></label>
    <div class="input-group">
        <?= Html::activeTextInput($model, "[$i][props]RAM_VOLUME", ['class' => 'form-control']); ?>
        <span class="input-group-addon">GB</span>
    </div>
</div>