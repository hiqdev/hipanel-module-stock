<?php

use hipanel\modules\stock\grid\MoveGridView;
use hipanel\modules\stock\grid\PartGridView;
use hipanel\modules\stock\menus\PartDetailMenu;
use hipanel\modules\stock\models\Part;
use hipanel\modules\stock\widgets\HardwareSettingsDetail;
use hipanel\widgets\IndexPage;
use hipanel\widgets\MainDetails;
use hiqdev\hiart\ActiveDataProvider;
use yii\helpers\Html;

/**
 * @var Part $model
 * @var ActiveDataProvider $moveDataProvider
 */

$this->title = html::encode(yii::t('hipanel', $model->title));
$this->params['subtitle'] = yii::t('hipanel', 'detailed information');
$this->params['breadcrumbs'][] = ['label' => yii::t('hipanel:stock', 'parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = yii::t('hipanel', $this->title);

?>

<div class="row">
    <div class="col-lg-3">

        <?= MainDetails::widget([
            'title' => Yii::t('hipanel:stock', $model->model_type_label . ' ' . $model->model_brand_label),
            'subTitle' => (function () use ($model) {
                if ($model->state !== Part::STATE_OK) {
                    $state = Yii::t('hipanel', 'Deleted');
                    $label = "<br /><span class=\"label label-danger\">{$state}</span>";
                }

                return $model->partno . ' #' . $model->serial . ($label ?? '') ;
            })(),
            'icon' => 'fa-cubes',
            'menu' => PartDetailMenu::widget(['model' => $model], ['linkTemplate' => '<a href="{url}" {linkOptions}><span class="pull-right">{icon}</span>{label}</a>']),
        ]) ?>

        <div class="row">
            <div class="col-lg-12">
                <?php if (Yii::$app->user->can('model.update')) : ?>
                    <?= HardwareSettingsDetail::widget(['id' => $model->model_id, 'type' => $model->model_type]) ?>
                <?php endif ?>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <?= Html::tag('h3', Yii::t('hipanel', 'Detailed information'), ['class' => 'box-title']) ?>
                    </div>
                    <div class="box-body no-padding">
                        <div class="table-responsive">
                            <?= PartGridView::detailView([
                                'boxed' => false,
                                'model' => $model,
                                'columns' => [
                                    'model_type_label',
                                    'model_brand_label',
                                    'partno',
                                    'model',
                                    'model_group',
                                    ['attribute' => 'serial'],
                                    'last_move',
                                    'move_type_label',
                                    'move_time',
                                    'dc_ticket',
                                    'place',
                                    'company',
                                    'reserve',
                                    'first_move',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header with-border">
                        <?= Html::tag('h3', Yii::t('hipanel', 'Sale detailed information'), ['class' => 'box-title']) ?>
                        <?php if ($model->is_sold_to_client) : ?>
                            <div class="box-tools pull-right">
                                <?= Html::tag('div', Yii::t('hipanel:stock', 'SOLD TO THE CLIENT'), ['class' => 'label label-danger']) ?>
                            </div>
                        <?php endif ?>
                    </div>
                    <div class="box-body no-padding">
                        <div class="table-responsive">
                            <?= PartGridView::detailView([
                                'boxed' => false,
                                'model' => $model,
                                'columns' => [
                                    'price',
                                    'selling_price',
                                    'in-sale-buyer',
                                    'sale',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (Yii::$app->user->can('move.read')) : ?>
                <div class="col-md-12">
                    <?php $page = IndexPage::begin(['model' => $model, 'layout' => 'noSearch']) ?>
                    <?php $page->beginContent('show-actions') ?>
                    <h3 class="box-title" style="display: inline-block;"><?= Yii::t('hipanel:stock', 'Move history') ?></h3>

                    <?php $page->endContent() ?>

                    <?php $page->beginContent('bulk-actions') ?>
                    <?php if (Yii::$app->user->can('move.delete')) : ?>
                        <?= $page->renderBulkDeleteButton('@move/delete') ?>
                    <?php endif ?>
                    <?php $page->endContent() ?>

                    <?php $page->beginContent('table') ?>
                    <?php $page->beginBulkForm() ?>
                    <?= MoveGridView::widget([
                        'boxed' => false,
                        'dataProvider' => $moveDataProvider,
                        'filterModel' => $model,
                        'tableOptions' => [
                            'class' => 'table table-striped table-bordered',
                        ],
                        'filterRowOptions' => ['style' => 'display: none;'],
                        'columns' => [
                            'checkbox',
                            'client',
                            'date',
                            'move',
                            'descr',
                            'parts',
                            'data',
                        ],
                    ]) ?>
                    <?php $page->endBulkForm() ?>
                    <?php $page->endContent() ?>
                    <?php $page->end() ?>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>
