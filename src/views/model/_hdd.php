<?php
use yii\helpers\Html;

?>

<div class="form-group">
    <label for="exampleInputEmail1"><?= Html::activeLabel($model, 'FORMFACTOR')?></label>
<?= Html::activeTextInput($model, "[$i][props]FORMFACTOR", ['class' => 'form-control']); ?>
</div>