<?php
use hipanel\modules\stock\widgets\combo\UsertagCombo;
use yii\helpers\Html;
use yii\web\JsExpression;

?>

<div class="form-group">
    <label><?= Html::activeLabel($model, 'prop_tags')?></label>
    <?= UsertagCombo::widget([
        'model' => $model,
        'attribute' => "[$i]prop_tags",
        'pluginOptions' => [
//        'clearWhen' => ['model/type'],
            'select2Options' => [
                'multiple' => true,
            ],
        ],
        'filter' => [
            'type' => [
                'field' => 'model/type',
                'format' => new JsExpression('function (id, text, field) {
                                            return "type,model,cpu";
                                        }'),
            ],
        ],
    ]) ?>
</div>

