<?php

use hipanel\modules\stock\grid\MoveGridView;
use hipanel\modules\stock\grid\PartGridView;
use hipanel\modules\stock\menus\PartDetailMenu;
use hipanel\widgets\Box;
use hipanel\widgets\IndexPage;
use hipanel\widgets\MainDetails;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Html::encode($model->title);
$this->params['subtitle'] = Yii::t('hipanel', 'detailed information');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:stock', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-lg-3">

        <?= MainDetails::widget([
            'title' => $model->model_type_label . ' ' . $model->model_brand_label,
            'subTitle' => (function () use ($model) {
                if ($model->state !== \hipanel\modules\stock\models\Part::STATE_OK) {
                    $state = Yii::t('hipanel', 'Deleted');
                    $label = "<br /><span class=\"label label-danger\">{$state}</span>";
                }

                return $model->partno . ' #' . $model->serial . ($label ?? '') ;
            })(),
            'icon' => 'fa-cubes',
            'menu' => PartDetailMenu::widget(['model' => $model], ['linkTemplate' => '<a href="{url}" {linkOptions}><span class="pull-right">{icon}</span>&nbsp;{label}</a>']),
        ]) ?>

        <div class="row">
            <div class="col-lg-12">
                <?php $box = Box::begin(['renderBody' => false]) ?>
                    <?php $box->beginHeader() ?>
                        <?= $box->renderTitle(Yii::t('hipanel', 'Detailed information')) ?>
                    <?php $box->endHeader() ?>
                    <?php $box->beginBody() ?>
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
                                    'price',
                                    'place',
                                    'company',
                                    'reserve',
                                    'order_no',
                                ],
                            ]) ?>
                        </div>
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
                            ],
                        ]) ?>
                    <?php $page->endBulkForm() ?>
            <?php $page->endContent() ?>
        <?php $page->end() ?>
    </div>
</div>
