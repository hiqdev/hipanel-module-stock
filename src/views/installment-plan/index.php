<?php

use hipanel\models\IndexPageUiOptions;
use hipanel\modules\stock\grid\InstallmentPlanGridLegend;
use hipanel\modules\stock\grid\InstallmentPlanGridView;
use hipanel\modules\stock\grid\InstallmentPlanRepresentations;
use hipanel\modules\stock\models\InstallmentPlanSearch;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\IndexPage;
use yii\data\ActiveDataProvider;
use yii\web\View;

/**
 * @var View $this
 * @var InstallmentPlanSearch $model
 * @var IndexPageUiOptions $uiModel
 * @var InstallmentPlanRepresentations $representationCollection
 * @var ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('hipanel:stock', 'Installment plans');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $page = IndexPage::begin(['model' => $model, 'dataProvider' => $dataProvider]) ?>

    <?php $page->beginContent('legend') ?>
        <?= GridLegend::widget(['legendItem' => new InstallmentPlanGridLegend($model)]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('sorter-actions') ?>
        <?= $page->renderSorter(['attributes' => ['id', 'since', 'till']]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('representation-actions') ?>
        <?= $page->renderRepresentations($representationCollection) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('bulk-actions') ?>
        <?php if (Yii::$app->user->can('installment-plan.delete')): ?>
            <?= $page->renderBulkDeleteButton('delete', Yii::t('hipanel:stock', 'Delete')) ?>
        <?php endif ?>
        <?php if (Yii::$app->user->can('installment-plan.restore')): ?>
            <?= $page->renderBulkButton('restore', Yii::t('hipanel:stock', 'Restore')) ?>
        <?php endif ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
            <?= InstallmentPlanGridView::widget([
                'boxed' => false,
                'dataProvider' => $dataProvider,
                'filterModel' => $model,
                'columns' => $representationCollection->getByName($uiModel->representation)->getColumns(),
            ]) ?>
        <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>

<?php $page->end() ?>
