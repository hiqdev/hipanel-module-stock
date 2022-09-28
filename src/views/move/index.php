<?php

use hipanel\models\IndexPageUiOptions;
use hipanel\modules\stock\grid\MoveGridLegend;
use hipanel\modules\stock\grid\MoveGridView;
use hipanel\modules\stock\models\MoveSearch;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\IndexPage;
use hiqdev\hiart\ActiveDataProvider;
use hiqdev\higrid\representations\RepresentationCollection;

/**
 * @var MoveSearch $model
 * @var ActiveDataProvider $dataProvider
 * @var RepresentationCollection $representationCollection
 * @var IndexPageUiOptions $uiModel
 * @var array $types
 */

$this->title = Yii::t('hipanel:stock', 'Moves');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $page = IndexPage::begin(['model' => $model, 'dataProvider' => $dataProvider]) ?>
    <?php $page->setSearchFormData(['types' => $types]) ?>

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
        <?php if (Yii::$app->user->can('move.delete')) : ?>
            <?= $page->renderBulkDeleteButton('delete') ?>
        <?php endif ?>
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
                'columns' => $representationCollection->getByName($uiModel->representation)->getColumns(),
            ]) ?>
        <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>

<?php $page->end() ?>
