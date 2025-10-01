<?php

use hipanel\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/** @var array $models */
/** @var array $types */
/** @var array $groupedModels */
/** @var array $remotehands */
/** @var array $remotehands */

$scenario = $this->context->action->scenario;
$this->title = Yii::t('hipanel:stock', 'Bulk move');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:stock', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin([
    'id' => 'dynamic-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => 'move']),
]) ?>

<div class="container-items">

    <?php if (empty($groupedModels)) : ?>
        <?php foreach ($models as $src_id => $group) : ?>
            <?= $this->render('_move', compact(['src_id', 'group', 'form', 'remotehands', 'types'])) ?>
        <?php endforeach; ?>
    <?php else: ?>
        <?php foreach ($groupedModels as $preGroup) : ?>
            <?php foreach ($preGroup as $src_id => $group) : ?>
                <?= $this->render('_move', compact(['src_id', 'group', 'form', 'remotehands', 'types'])) ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12 no">
            <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
            &nbsp;
            <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
