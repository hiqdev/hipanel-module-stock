<?php

use hipanel\modules\stock\grid\ModelGridView;
use hipanel\modules\stock\grid\PartGridView;
use hipanel\modules\stock\menus\ModelDetailMenu;
use hipanel\modules\stock\widgets\HardwareSettingsDetail;
use hipanel\widgets\IndexPage;
use hipanel\widgets\MainDetails;
use yii\data\ArrayDataProvider;
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
            'icon' => 'fa-cube',
            'subTitle' => Html::encode(Yii::t('hipanel:stock', $model->type_label) . ' / ' . $model->brand_label),
            'menu' => ModelDetailMenu::widget(['model' => $model], ['linkTemplate' => '<a href="{url}" {linkOptions}><span class="pull-right">{icon}</span>&nbsp;{label}</a>']),
        ]) ?>

        <div class="box box-widget">
            <div class="box-body no-padding">
                <?= ModelGridView::detailView([
                    'boxed' => false,
                    'model' => $model,
                    'columns' => [
                        'type', 'brand', 'model',
                        'partno', 'short', 'descr',
                        'last_prices', 'model_group'
                    ],
                ]) ?>
            </div>
        </div>
        <?= HardwareSettingsDetail::widget(['id' => $model->id, 'type' => $model->type]) ?>
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
                            'order_name',
                        ],
                    ]) ?>
                <?php $page->endBulkForm() ?>
            <?php $page->endContent() ?>

        <?php $page->end() ?>
    </div>
</div>
