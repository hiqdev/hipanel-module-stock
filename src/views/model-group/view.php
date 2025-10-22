<?php

use hipanel\modules\stock\grid\ModelGridView;
use hipanel\modules\stock\grid\ModelGroupGridView;
use hipanel\modules\stock\menus\ModelGroupDetailMenu;
use hipanel\modules\stock\models\ModelGroup;
use hipanel\widgets\IndexPage;
use hipanel\widgets\MainDetails;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var ModelGroup $model
 */

$this->title = Html::encode($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:stock', 'Model groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">

    <div class="col-md-3">
        <?= MainDetails::widget([
            'title' => $this->title,
            'icon' => 'fa-folder-open',
            'subTitle' => Html::encode($model->descr),
            'menu' => ModelGroupDetailMenu::widget(
                ['model' => $model],
                ['linkTemplate' => '<a href="{url}" {linkOptions}><span class="pull-right">{icon}</span>&nbsp;{label}</a>']
            ),
        ]) ?>

        <div>
            <div class="box box-widget">
                <div class="box-body no-padding">
                    <?= ModelGroupGridView::detailView([
                        'model' => $model,
                        'boxed' => false,
                        'gridOptions' => [
                            'filterModel' => $model,
                        ],
                        'columns' => ['tableInfoRow', ...$model->getStockList()],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <?php $page = IndexPage::begin(['model' => $model, 'layout' => 'noSearch']) ?>

            <?php $page->beginContent('show-actions') ?>
                <h4 class="box-title" style="display: inline-block;">&nbsp;<?= Yii::t('hipanel:stock', 'Models') ?></h4>
            <?php $page->endContent() ?>

            <?php $page->beginContent('table') ?>
                <?php $page->beginBulkForm() ?>
                    <?= ModelGridView::widget([
                        'boxed' => false,
                        'dataProvider' => new ArrayDataProvider([
                            'allModels' => $model->model_ids ? $model->models : [],
                            'pagination' => [
                                'pageSize' => 50,
                            ],
                        ]),
                        'tableOptions' => [
                            'class' => 'table table-striped table-bordered'
                        ],
                        'columns' => [
                            'type',
                            'brand',
                            'model',
                            'descr',
                            'partno',
                            ...array_map(fn(string $name): array => [
                                'attribute' => implode(':', ['stock_alias', $name]),
                                'label' => $name,
                            ], $model->getStockList()),
                            'last_prices',
                        ],
                    ]) ?>
                <?php $page->endBulkForm() ?>
            <?php $page->endContent() ?>
        <?php $page->end() ?>
    </div>
</div>
