<?php
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hiqdev\combo\StaticCombo;
?>

<div class="col-md-3">
    <?= $search->field('partno_like')->widget(PartnoCombo::classname()) ?>
    <?= $search->field('type')->widget(StaticCombo::classname(), [
        'data' => $types,
        'hasId' => true,
        'pluginOptions' => [
            'select2Options' => [
                'multiple' => false,
            ],
        ],
    ]) ?>
</div>
<!-- /.col-md-3 -->
<div class="col-md-3">
    <?= $search->field('brand')->widget(StaticCombo::classname(), [
        'data' => $brands,
        'hasId' => true,
        'pluginOptions' => [
            'select2Options' => [
                'multiple' => false,
            ],
        ],
    ]) ?>
    <?= $search->field('model_like') ?>
</div>
<!-- /.col-md-3 -->
<div class="col-md-3">
    <?= $search->field('short_like') ?>
    <?= $search->field('descr_like') ?>
</div>
<!-- /.col-md-3 -->
<div class="col-md-3">
    <?= $search->field('group_like') ?>
    <?= $search->field('show_hidden_from_user')->widget(StaticCombo::classname(), [
        'data' => [
            '0' => Yii::t('app', 'Hide hidden'),
            '1' => Yii::t('app', 'Show hidden'),
        ],
        'hasId' => true,
        'pluginOptions' => [
            'select2Options' => [
                'multiple' => false,
            ],
        ],
    ]) ?>
</div>
<!-- /.col-md-3 -->
