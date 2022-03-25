<?php
use yii\helpers\Html;
$a = 1;

?>

<div class="form-group">
    <label class="control-label"><?= Html::activeLabel($model, 'RAM_VOLUME')?></label>
    <div class="input-group">
        <?= Html::activeTextInput($model, "[$i][props]RAM_VOLUME", ['class' => 'form-control', 'value' => $model->props['RAM_VOLUME'] ?? null]) ?>
        <span class="input-group-addon">GB</span>
    </div>
</div>
