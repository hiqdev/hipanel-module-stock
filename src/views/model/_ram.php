<?php
use yii\helpers\Html;

?>

<div class="form-group">
    <label for="exampleInputEmail1"><?= Html::activeLabel($model, 'RAM_VOLUME')?></label>
    <?= Html::activeTextInput($model, "[$i][props]RAM_VOLUME", ['class' => 'form-control']); ?>
</div>
