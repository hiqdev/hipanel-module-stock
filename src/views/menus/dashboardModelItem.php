<?php

use hipanel\modules\dashboard\widgets\SmallBox;
use hipanel\modules\dashboard\widgets\SearchForm;
use hipanel\modules\stock\models\ModelSearch;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
    <?php $box = SmallBox::begin([
        'boxTitle' => Yii::t('hipanel:stock', 'Models'),
        'boxIcon' => 'fa-cubes',
        'boxColor' => SmallBox::COLOR_BLUE,
    ]) ?>
    <?php $box->beginBody() ?>
    <br>
    <br>
    <?= SearchForm::widget([
        'formOptions' => [
            'id' => 'model-search',
            'action' => Url::to('@model/index'),
        ],
        'model' => new ModelSearch(),
        'attribute' => 'model_like',
        'buttonColor' => SmallBox::COLOR_BLUE,
    ]) ?>
    <?php $box->endBody() ?>
    <?php $box->beginFooter() ?>
    <?= Html::a(Yii::t('hipanel', 'View') . $box->icon(), '@model/index', ['class' => 'small-box-footer']) ?>
    <?php if (Yii::$app->user->can('model.create')) : ?>
        <?= Html::a(Yii::t('hipanel', 'Create') . $box->icon('fa-plus'), '@model/create', ['class' => 'small-box-footer']) ?>
    <?php endif ?>
    <?php $box->endFooter() ?>
    <?php $box::end() ?>
</div>
