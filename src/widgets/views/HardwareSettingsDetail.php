<?php

use hipanel\widgets\Box;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var array $props */

?>

<?php $box = Box::begin([
    'renderBody' => false,
    'options' => ['id' => $this->context->id, 'class' => 'box-widget', 'style' => ['min-height' => '4em']],
    'bodyOptions' => ['class' => 'no-padding'],
    'headerOptions' => ['class' => 'with-border'],
]) ?>

<?php $box->beginHeader() ?>
<?= $box->renderTitle(Yii::t('hipanel:stock', 'Hardware properties')) ?>
<?php $box::endHeader() ?>

<?php if (empty($props)) : ?>
    <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
<?php else: ?>
    <?php foreach ($props as $type => $settings) : ?>
        <?= Html::tag('h5', mb_strtoupper($type), ['class' => 'text-bold', 'style' => [
            'padding' => '8px',
            'border-bottom' => '2px solid #CCCCCC',
            'margin-bottom' => '0px',
        ]]) ?>
        <?php $box->beginBody() ?>
        <?= DetailView::widget([
            'model' => $settings,
        ]) ?>
        <?php $box->endBody() ?>
    <?php endforeach ?>
<?php endif ?>


<?php Box::end() ?>
