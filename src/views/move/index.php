<?php
use hipanel\modules\stock\grid\MoveGridView;
use hipanel\widgets\IndexLayoutSwitcher;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;

$this->title = Yii::t('app', 'Moves');
$this->subtitle = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->breadcrumbs->setItems([
    $this->title,
]);
?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
    <?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>
        <?= $page->setSearchFormData(compact(['types'])) ?>

        <?php $page->beginContent('show-actions') ?>
        <?= IndexLayoutSwitcher::widget() ?>
        <?= $page->renderSorter([
            'attributes' => [
                'time',
                'client',
            ],
        ]) ?>
        <?= $page->renderPerPage() ?>
        <?= $page->renderRepresentation() ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('bulk-actions') ?>
            <?= $page->renderBulkButton(Yii::t('app', 'Delete'), ['delete'], 'danger') ?>
        <?php $page->endContent('bulk-actions') ?>

        <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
        <?= MoveGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $model,
            'boxed' => false,
            'columns' => [
                'checkbox',
                'client',
                'date',
                'move',
                'descr',
                'data',
                'parts',
                'actions',
            ],
        ]) ?>
        <?php $page->endBulkForm() ?>
        <?php $page->endContent() ?>

    <?php $page->end() ?>
<?php Pjax::end() ?>
