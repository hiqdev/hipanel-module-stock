<?php

use hipanel\modules\stock\grid\ModelGridView;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use yii\helpers\Html;

$this->title = Yii::t('hipanel:stock', 'Models');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>

<?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>

    <?= $page->setSearchFormData(compact(['types', 'brands'])) ?>
    <?php $page->beginContent('main-actions') ?>
        <?= Html::a(Yii::t('hipanel', 'Create model'), 'create', ['class' => 'btn btn-sm btn-success']) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('bulk-actions') ?>
        <?= $page->renderBulkButton(Yii::t('hipanel:stock', 'Show for users'), 'unmark-hidden-from-user') ?>
        <?= $page->renderBulkButton(Yii::t('hipanel:stock', 'Hide from users'), 'mark-hidden-from-user') ?>
        <?= $page->renderBulkButton(Yii::t('hipanel:stock', 'Update'), 'update') ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('show-actions') ?>
        <?= $page->renderLayoutSwitcher() ?>
        <?= $page->renderSorter([
            'attributes' => [
                'type', 'brand', 'model',
            ],
        ]) ?>
        <?= $page->renderPerPage() ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
        <?= ModelGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $model,
            'boxed' => false,
            'columns' => [
                'checkbox',
                'type', 'brand', 'model',
                'descr', 'partno',
                'dtg', 'sdg', 'm3',
                'last_prices',
            ],
        ]) ?>
        <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>
<?php $page->end() ?>
<?php Pjax::end() ?>
