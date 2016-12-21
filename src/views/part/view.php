<?php

use hipanel\modules\stock\grid\MoveGridView;
use hipanel\modules\stock\grid\PartGridView;
use hipanel\modules\stock\menus\PartDetailMenu;
use hipanel\widgets\Box;
use hipanel\widgets\IndexPage;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Html::encode(sprintf('%s %s %s #%s', $model->model_type_label, $model->model_brand_label, $model->partno, $model->serial));
$this->params['subtitle'] = Yii::t('hipanel', 'detailed information');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:stock', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-lg-3">
        <div class="row">
            <div class="col-lg-12">
                <?php Box::begin([
                    'options' => [
                        'class' => 'box-solid',
                    ],
                    'bodyOptions' => [
                        'class' => 'no-padding',
                    ],
                ]) ?>
                <div class="profile-user-img text-center">
                    <i class="fa fa-cubes fa-5x"></i>
                </div>
                <p class="text-center">
                    <span class="profile-user-role"><?= $model->type ?></span>
                    <br>
                    <span class="profile-user-name">
                        <?= $model->model_type_label . ' ' . $model->model_brand_label . '<br>' . $model->partno . ' #' . $model->serial ?>
                    </span>
                </p>
                <div class="profile-usermenu">
                    <?= PartDetailMenu::widget(['model' => $model]) ?>
                </div>
                <?php Box::end() ?>

            </div>
            <div class="col-lg-12">
                <?php $box = Box::begin(['renderBody' => false]) ?>
                <?php $box->beginHeader() ?>
                <?= $box->renderTitle(Yii::t('hipanel', 'Detailed information')) ?>
                <?php $box->endHeader() ?>
                <?php $box->beginBody() ?>
                <?= PartGridView::detailView([
                    'boxed' => false,
                    'model' => $model,
                    'columns' => [
                        'model_type_label', 'model_brand_label',
                        'partno', 'model', ['attribute' => 'serial'],
                        'last_move', 'move_type_label', 'move_time',
                        'order_data', 'dc_ticket',
                        'price', 'place',
                    ],
                ]) ?>
                <?php $box->endBody() ?>
                <?php $box->end() ?>

            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <?php $page = IndexPage::begin(['model' => $model, 'layout' => 'noSearch']) ?>
        <?php $page->beginContent('show-actions') ?>
        <h4 class="box-title" style="display: inline-block;"><?= Yii::t('hipanel:stock', 'Move history') ?></h4>
        <?php $page->endContent() ?>
        <?php $page->beginContent('bulk-actions') ?>
        <?= $page->renderBulkButton(Yii::t('hipanel', 'Delete'), Url::toRoute('@move/delete'), 'danger') ?>
        <?php $page->endContent() ?>
        <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
        <?= MoveGridView::widget([
            'boxed' => false,
            'dataProvider' => $moveDataProvider,
            'filterModel' => $model,
            'tableOptions' => [
                'class' => 'table table-striped table-bordered'
            ],
            'filterRowOptions' => ['style' => 'display: none;'],
            'columns' => [
                'checkbox', 'client', 'date',
                'move', 'descr', 'parts',
            ],
        ]) ?>
        <?php $page->endBulkForm() ?>
        <?php $page->endContent() ?>
        <?php $page->end() ?>
    </div>
</div>
