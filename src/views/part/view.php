<?php

use hipanel\modules\stock\grid\PartGridView;
use hipanel\widgets\Box;
use yii\helpers\Html;

$this->title    = Html::encode(sprintf('%s %s %s #%s', $model->model_type_label, $model->model_brand_label, $model->partno, $model->serial));
$this->subtitle = Yii::t('app','Part details') . ' ' . $this->title;
$this->breadcrumbs->setItems([
    ['label' => Yii::t('app', 'Parts'), 'url' => ['index']],
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
            <span class="profile-user-name">
                <?= $model->model_type_label . ' ' . $model->model_brand_label . '<br>' . $model->partno . ' ' . $model->serial; ?>
            </span>
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
                echo PartGridView::detailView([
                    'boxed' => false,
                    'model' => $model,
                    'columns' => [
                        'main',
                        'partno',
                        'last_move',
                        'move_type_label',
                        'move_time',
                        'order_data',
                        'DC_ticket_ID',
                    ],
                ]);
                $box->endBody();
                $box->end();
                ?>
            </div>
        </div>
    </div>
</div>
