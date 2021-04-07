<?php
/**
 * @var \yii\web\View $this
 */

use hipanel\modules\stock\grid\OrderGridView;
use hipanel\modules\stock\grid\PartGridView;
use hipanel\modules\stock\menus\OrderDetailMenu;
use hipanel\modules\stock\models\Order;
use hipanel\modules\stock\widgets\OrderFileRender;
use hipanel\widgets\Box;
use hipanel\widgets\IndexPage;
use hipanel\widgets\MainDetails;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

/** @var $model Order */

$this->title = Html::encode($model->pageTitle);
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel.stock.order', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-12">
                <?= MainDetails::widget([
                    'title' => $model->pageTitle,
                    'icon' => 'fa-shopping-basket',
                    'subTitle' => Html::a($model->buyer, ['@order/view', 'id' => $model->buyer_id]),
                    'menu' => OrderDetailMenu::widget(['model' => $model], ['linkTemplate' => '<a href="{url}" {linkOptions}><span class="pull-right">{icon}</span>&nbsp;{label}</a>']),
                ]) ?>
            </div>
            <div class="col-md-12">
                <?php $box = Box::begin([
                        'renderBody' => false,
                        'bodyOptions' => ['class' => 'no-padding'],
                        'options' => ['class' => 'box-widget'],
                    ]) ?>
                    <?php $box->beginHeader() ?>
                        <?= $box->renderTitle(Yii::t('hipanel.stock.order', 'Details')) ?>
                    <?php $box->endHeader() ?>
                    <?php $box->beginBody() ?>
                        <?= OrderGridView::detailView([
                            'model' => $model,
                            'boxed' => false,
                            'columns' => [
                                'type',
                                'state',
                                'seller',
                                'buyer',
                                'name',
                                'time',
                            ],
                        ]) ?>
                    <?php $box->endBody() ?>
                <?php $box->end() ?>
            </div>

            <div class="col-md-12">
                <?php if ($model->files) : ?>
                    <div class="box box-widget">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <?= Yii::t('hipanel:stock', 'File attachments') ?>
                            </h3>
                        </div>
                        <div class="box-body no-padding">
                            <ul class="nav nav-stacked">
                                <?php foreach ($model->files as $file) : ?>
                                    <li>
                                        <?= OrderFileRender::widget(['file' => $file]) ?>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <?php $page = IndexPage::begin(['model' => $model, 'layout' => 'noSearch']) ?>

        <?php $page->beginContent('show-actions') ?>
            <h4 class="box-title" style="display: inline-block;">&nbsp;<?= Yii::t('hipanel:stock', 'Parts') ?></h4>
        <?php $page->endContent() ?>

        <?php $page->beginContent('table') ?>
            <?php $page->beginBulkForm() ?>
                <?= PartGridView::widget([
                    'boxed' => false,
                    'dataProvider' => new ArrayDataProvider([
                        'allModels' => $model->parts,
                        'pagination' => [
                            'pageSize' => 25,
                        ],
                    ]),
                    'tableOptions' => [
                        'class' => 'table table-striped table-bordered'
                    ],
                    'columns' => [
                        'model_type', 'model_brand', 'partno', 'serial',
                        'last_move', 'move_type_and_date', 'move_descr',
                        'price',
                    ],
                ]) ?>
            <?php $page->endBulkForm() ?>
        <?php $page->endContent() ?>

        <?php $page->end() ?>
    </div>
</div>
