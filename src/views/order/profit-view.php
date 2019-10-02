<?php
/**
 * @var \yii\web\View $this
 * @var Order $model
 */

use hipanel\modules\stock\grid\OrderGridView;
use hipanel\modules\stock\grid\PartGridView;
use hipanel\modules\stock\helpers\ProfitColumns;
use hipanel\modules\stock\menus\OrderDetailMenu;
use hipanel\modules\stock\models\Order;
use hipanel\widgets\Box;
use hipanel\widgets\IndexPage;
use hipanel\widgets\MainDetails;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

$this->title = Html::encode($model->pageTitle) . ' Profit';
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
                <?php $box = Box::begin(['renderBody' => false]) ?>
                    <?php $box->beginHeader() ?>
                        <?= $box->renderTitle(Yii::t('hipanel.stock.order', 'Details')) ?>
                    <?php $box->endHeader() ?>
                    <?php $box->beginBody() ?>
                        <?= OrderGridView::detailView([
                            'model' => $model,
                            'boxed' => false,
                            'columns' => [
                                'id',
                                'type',
                                'state',
                                'seller',
                                'buyer',
                                'comment',
                                'time',
                            ],
                        ]) ?>
                    <?php $box->endBody() ?>
                <?php $box->end() ?>
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
                        'allModels' => $model->partsProfit,
                        'pagination' => [
                            'pageSize' => 25,
                        ],
                    ]),
                    'tableOptions' => [
                        'class' => 'table table-striped table-bordered'
                    ],
                    'showFooter' => true,
                    'columns' => ProfitColumns::getColumns(['serial', 'partno', 'model_brand_label']),
                ]) ?>
            <?php $page->endBulkForm() ?>
        <?php $page->endContent() ?>

        <?php $page->end() ?>
    </div>
</div>
