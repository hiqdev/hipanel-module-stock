<?php

use hipanel\models\IndexPageUiOptions;
use hipanel\modules\stock\grid\ModelGridLegend;
use hipanel\modules\stock\grid\ModelGridView;
use hipanel\modules\stock\models\ModelSearch;
use hipanel\modules\stock\widgets\StockLocationsListTreeSelect;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use hiqdev\higrid\representations\RepresentationCollection;
use hiqdev\hiart\ActiveDataProvider;
use yii\helpers\Html;

/**
 * @var ModelSearch $model
 * @var RepresentationCollection $representationCollection
 * @var ActiveDataProvider $dataProvider
 * @var Closure $exportVariants
 * @var IndexPageUiOptions $uiModel
 * @var array $types
 * @var array $brands
 * @var array $states
 */

$this->title = Yii::t('hipanel:stock', 'Models');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $page = IndexPage::begin(['model' => $model, 'dataProvider' => $dataProvider, 'exportVariants' => $exportVariants]) ?>

    <?php $page->setSearchFormData(['types' => $types, 'brands' => $brands, 'states' => $states]) ?>
    <?php $page->beginContent('main-actions') ?>
        <?php if (Yii::$app->user->can('model.create')) : ?>
            <?= Html::a(Yii::t('hipanel:stock', 'Create model'), 'create', ['class' => 'btn btn-sm btn-success']) ?>
        <?php endif ?>
        <?= StockLocationsListTreeSelect::widget() ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('legend') ?>
        <?= GridLegend::widget(['legendItem' => new ModelGridLegend($model)]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('bulk-actions') ?>
        <?php if (Yii::$app->user->can('model.update')) : ?>
            <?= $page->renderBulkButton('unmark-hidden-from-user', Yii::t('hipanel:stock', 'Show for users')) ?>
            <?= $page->renderBulkButton('mark-hidden-from-user', Yii::t('hipanel:stock', 'Hide from users')) ?>
            <?= $page->renderBulkButton('update', Yii::t('hipanel:stock', 'Update')) ?>
        <?php endif ?>
        <?php if (Yii::$app->user->can('model.create')) : ?>
            <?= $page->renderBulkButton('copy', Yii::t('hipanel:stock', 'Copy')) ?>
        <?php endif ?>
        <?php if (Yii::$app->user->can('model.delete')) : ?>
            <?= $page->renderBulkDeleteButton('delete') ?>
        <?php endif ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('sorter-actions') ?>
        <?= $page->renderSorter([
            'attributes' => [
                'type', 'brand', 'model', 'counters'
            ],
        ]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
        <?php Pjax::begin(['id' => 'actualize-locations']) ?>
            <?= ModelGridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $model,
                'boxed' => false,
                'columns' => $representationCollection->getByName($uiModel->representation)->getColumns(),
            ]) ?>
        <?php Pjax::end() ?>
        <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>

<?php IndexPage::end() ?>
