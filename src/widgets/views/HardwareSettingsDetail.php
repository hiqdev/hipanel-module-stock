<?php

use hipanel\modules\stock\widgets\HardwareSettingsButton;
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
    <div class="box-tools pull-right">
        <?= HardwareSettingsButton::widget([
            'id' => $this->context->id,
            'type' => $this->context->type,
            'toggleButton' => [
                'tag' => 'button',
                'class' => 'btn btn-xs',
                'label' => Yii::t('hipanel:stock', 'Edit properties'),
            ]
        ]) ?>
    </div>
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
            'template' => '<tr><th{captionOptions} width="50%">{label}</th><td{contentOptions} width="50%" align="center">{value}</td></tr>'
        ]) ?>
        <?php $box->endBody() ?>
    <?php endforeach ?>
<?php endif ?>


<?php Box::end() ?>
