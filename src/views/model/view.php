<?php

use hipanel\modules\stock\grid\ModelGridView;
use hipanel\modules\stock\menus\ModelDetailMenu;
use hipanel\widgets\MainDetails;
use yii\helpers\Html;

$this->title = $model->partno;
$this->params['subtitle'] = Yii::t('hipanel:stock', 'Model details') . ' ' . Html::encode($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:stock', 'Models'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-md-3">
        <?= MainDetails::widget([
            'title' => $this->title,
            'icon' => 'fa-cubes',
            'subTitle' => Html::encode($model->type_label . ' / ' . $model->brand_label),
            'menu' => ModelDetailMenu::widget(['model' => $model], ['linkTemplate' => '<a href="{url}" {linkOptions}><span class="pull-right">{icon}</span>&nbsp;{label}</a>']),
        ]) ?>

        <div class="box box-widget">
            <div class="box-body no-padding">
                <?= ModelGridView::detailView([
                    'boxed' => false,
                    'model' => $model,
                    'columns' => [
                        'type', 'brand', 'model',
                        'partno', 'descr',
                        'last_prices', 'model_group'
                    ],
                ]) ?>
            </div>
        </div>
    </div>

    <div class="col-md-9">

    </div>
</div>
