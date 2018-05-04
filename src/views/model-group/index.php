<?php

use hipanel\modules\stock\grid\ModelGroupGridView;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use yii\helpers\Html;

$this->title = Yii::t('hipanel:stock', 'Model groups');
$this->params['breadcrumbs'][] = $this->title;

/**
 * @var \yii\web\View $this
 * @var \hiqdev\hiart\ActiveDataProvider $dataProvider
 * @var \hipanel\modules\stock\models\ModelGroupSearch $model
 */

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>

<?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>
    <?php $page->beginContent('main-actions') ?>
        <?= Html::a(Yii::t('hipanel:stock', 'Create group'), 'create', ['class' => 'btn btn-sm btn-success']) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('bulk-actions') ?>
        <?= $page->renderBulkButton(Yii::t('hipanel:stock', 'Update'), 'update') ?>
        <?= $page->renderBulkButton(Yii::t('hipanel:stock', 'Copy'), 'copy') ?>
        <?php if (Yii::$app->user->can('model.delete')) : ?>
            <?= $page->renderBulkButton(Yii::t('hipanel:stock', 'Delete'), 'delete', 'danger') ?>
        <?php endif; ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('sorter-actions') ?>
        <?= $page->renderSorter([
            'attributes' => [
            ],
        ]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
        <?= ModelGroupGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $model,
            'boxed' => false,
            'columns' => array_merge(['checkbox', 'name'], array_keys($model->getSupportedLimitTypes()), ['descr'])
        ]) ?>
        <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>
<?php $page->end() ?>
<?php Pjax::end() ?>
