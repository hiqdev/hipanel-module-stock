<?php

use hipanel\modules\stock\grid\MoveGridLegend;
use hipanel\modules\stock\grid\MoveGridView;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;

$this->title = Yii::t('hipanel:stock', 'Moves');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
    <?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>
        <?= $page->setSearchFormData(compact(['types'])) ?>

        <?php $page->beginContent('sorter-actions') ?>
            <?= $page->renderSorter([
                'attributes' => [
                    'time',
                    'client',
                ],
            ]) ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('legend') ?>
            <?= GridLegend::widget(['legendItem' => new MoveGridLegend($model)]) ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('bulk-actions') ?>
            <?= $page->renderBulkButton(Yii::t('hipanel', 'Delete'), 'delete', 'danger') ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
        <?= MoveGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $model,
            'boxed' => false,
    'rowOptions' => function ($model) {
        return GridLegend::create(new MoveGridLegend($model))->gridRowOptions();
    },
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
<?php Pjax::end() ?>
