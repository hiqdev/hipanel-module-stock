<?php

use hipanel\modules\dashboard\widgets\SmallBox;
use hipanel\modules\dashboard\widgets\SearchForm;
use hipanel\modules\stock\models\PartSearch;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
    <?php $box = SmallBox::begin([
        'boxTitle' => Yii::t('hipanel:stock', 'Parts'),
        'boxIcon' => 'fa-cubes',
        'boxColor' => SmallBox::COLOR_YELLOW,
    ]) ?>
    <?php $box->beginBody() ?>
    <br>
    <br>
    <?= SearchForm::widget([
        'formOptions' => [
            'id' => 'part-search',
            'action' => Url::to('@part/index'),
        ],
        'model' => new PartSearch(),
        'attribute' => 'serial_like',
        'buttonColor' => SmallBox::COLOR_YELLOW,
    ]) ?>
    <?php $box->endBody() ?>
    <?php $box->beginFooter() ?>
    <?= Html::a(Yii::t('hipanel', 'View') . $box->icon(), '@part/index', ['class' => 'small-box-footer']) ?>
    <?php if (Yii::$app->user->can('part.create')) : ?>
        <?= Html::a(Yii::t('hipanel', 'Create') . $box->icon('fa-plus'), '@part/create', ['class' => 'small-box-footer']) ?>
    <?php endif ?>
    <?php $box->endFooter() ?>
    <?php $box::end() ?>
</div>
