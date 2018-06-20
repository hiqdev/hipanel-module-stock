<?php

use hipanel\modules\stock\grid\ModelGridLegend;
use hipanel\modules\stock\grid\ModelGridView;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use yii\helpers\Html;

$this->title = Yii::t('hipanel:stock', 'Models');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>

<?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>

    <?= $page->setSearchFormData(compact(['types', 'brands', 'states'])) ?>
    <?php $page->beginContent('main-actions') ?>
        <?= Html::a(Yii::t('hipanel:stock', 'Create model'), 'create', ['class' => 'btn btn-sm btn-success']) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('legend') ?>
        <?= GridLegend::widget(['legendItem' => new ModelGridLegend($model)]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('bulk-actions') ?>
        <?= $page->renderBulkButton('unmark-hidden-from-user', Yii::t('hipanel:stock', 'Show for users')) ?>
        <?= $page->renderBulkButton('mark-hidden-from-user', Yii::t('hipanel:stock', 'Hide from users')) ?>
        <?= $page->renderBulkButton('update', Yii::t('hipanel:stock', 'Update')) ?>
        <?= $page->renderBulkButton('copy', Yii::t('hipanel:stock', 'Copy')) ?>
        <?php if (Yii::$app->user->can('model.delete')) : ?>
            <?= $page->renderBulkDeleteButton('delete') ?>
        <?php endif; ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('sorter-actions') ?>
        <?= $page->renderSorter([
            'attributes' => [
                'type', 'brand', 'model',
            ],
        ]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
        <?= ModelGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $model,
            'boxed' => false,
            'columns' => $representationCollection->getByName($uiModel->representation)->getColumns(),
        ]) ?>
        <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>
<?php $page->end() ?>
<?php Pjax::end() ?>
