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
 * @var \hipanel\modules\stock\Module $module
 */

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>

<?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>
    <?php $page->beginContent('main-actions') ?>
        <?php if (Yii::$app->user->can('model.create')) : ?>
            <?= Html::a(Yii::t('hipanel:stock', 'Create group'), 'create', ['class' => 'btn btn-sm btn-success']) ?>
        <?php endif ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('bulk-actions') ?>
        <?php if (Yii::$app->user->can('model.update')) : ?>
            <?= $page->renderBulkButton('update', Yii::t('hipanel:stock', 'Update')) ?>
        <?php endif ?>
        <?php if (Yii::$app->user->can('model.create')) : ?>
            <?= $page->renderBulkButton('copy', Yii::t('hipanel:stock', 'Copy')) ?>
        <?php endif ?>
        <?php if (Yii::$app->user->can('model.delete')) : ?>
            <?= $page->renderBulkDeleteButton('delete') ?>
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
            'columns' => array_merge(['checkbox', 'name'], array_keys($module->stocksList), ['descr'])
        ]) ?>
        <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>
<?php $page->end() ?>
<?php Pjax::end() ?>
