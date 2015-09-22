<?php

use hipanel\modules\stock\grid\ModelGridView;
use hipanel\widgets\Box;
use yii\helpers\Html;

$this->title = Html::encode(sprintf('%s %s %s', $model->type, $model->brand_label, $model->model));
$this->subtitle = Yii::t('app', 'Model details') . ' ' . $this->title;
$this->breadcrumbs->setItems([
    ['label' => Yii::t('app', 'Models'), 'url' => ['index']],
    $this->title,
]);
?>

<div class="row">
    <div class="col-md-3">
        <?php Box::begin([
            'options' => [
                'class' => 'box-solid',
            ],
            'bodyOptions' => [
                'class' => 'no-padding',
            ],
        ]); ?>
        <div class="profile-user-img text-center">
            <i class="fa fa-cubes fa-5x"></i>
        </div>
        <p class="text-center">
            <span class="profile-user-role"><?= $model->type ?></span>
            <br>
            <span class="profile-user-name"><?= $model->brand_label . ' ' . $model->model; ?></span>
        </p>
        <div class="profile-usermenu">
            <ul class="nav">
                <li><?= Html::a('Some action 1', '#') ?></li>
                <li><?= Html::a('Some action 2', '#') ?></li>
                <li><?= Html::a('Some action 3', '#') ?></li>
            </ul>
        </div>
        <?php Box::end(); ?>
    </div>

    <div class="col-md-9">
        <div class="row">
            <div class="col-md-6">
                <?php
                $box = Box::begin(['renderBody' => false]);
                $box->beginHeader();
                echo $box->renderTitle(Yii::t('app', 'Information'));
                $box->endHeader();
                $box->beginBody();
                echo ModelGridView::detailView([
                    'boxed' => false,
                    'model' => $model,
                    'columns' => [
                        'type',
                        'brand',
                        'model',
                        'partno',
                        'descr',
                        'last_prices',
                    ],
                ]);
                $box->endBody();
                $box->end();
                ?>
            </div>
        </div>
    </div>
</div>
