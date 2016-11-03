<?php

use hipanel\modules\stock\grid\MoveGridView;
use hipanel\modules\stock\grid\PartGridView;
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
                    <ul class="nav">
                        <?php if ($model->reserve) : ?>
                            <li><?= Html::a('<i class="fa fa-history"></i>&nbsp;' . Yii::t('hipanel:stock', 'Unreserve'), ['@part/unreserve', 'id' => $model->id]) ?></li>
                        <?php else : ?>
                            <li><?= Html::a('<i class="fa fa-history"></i>&nbsp;' . Yii::t('hipanel:stock', 'Reserve'), ['@part/reserve', 'id' => $model->id]) ?></li>
                        <?php endif; ?>
                        <li><?= Html::a('<i class="fa fa-repeat"></i>&nbsp;' . Yii::t('hipanel:stock', 'Replace'), ['@part/replace', 'id' => $model->id]) ?></li>
                        <li><?= Html::a('<i class="fa fa-files-o"></i>&nbsp;' . Yii::t('hipanel:stock', 'Copy'), ['@part/copy', 'id' => $model->id]) ?></li>
                        <li><?= Html::a('<i class="fa fa-arrows-h"></i>&nbsp;' . Yii::t('hipanel:stock', 'Move by one'), ['@part/move-by-one', 'id' => $model->id]) ?></li>
                        <li><?= Html::a('<i class="fa fa-pencil"></i>&nbsp;' . Yii::t('hipanel', 'Update'), ['@part/update', 'id' => $model->id]) ?></li>
                        <li><?= Html::a('<i class="fa fa-trash-o"></i>&nbsp;' . Yii::t('hipanel:stock', 'Trash'), ['@part/trash', 'id' => $model->id]) ?></li>
                    </ul>
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
                        'model_type_label', 'model_brand_label', 'model', ['attribute' => 'serial'],
                        'last_move', 'move_type_label', 'move_time',
                        'order_data', 'DC_ticket_ID',
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
                'checkbox',
                'client',
                'date',
                'move',
                'descr',
                'data',
                'parts',
            ],
        ]) ?>
        <?php $page->endBulkForm() ?>
        <?php $page->endContent() ?>
        <?php $page->end() ?>
    </div>
</div>
