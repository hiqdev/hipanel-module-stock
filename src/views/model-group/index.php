<?php

use hipanel\modules\stock\grid\ModelGroupGridView;
use hipanel\modules\stock\models\ModelGroupSearch;
use hipanel\modules\stock\repositories\StockRepository;
use hipanel\widgets\IndexPage;
use hiqdev\hiart\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var ModelGroupSearch $model
 * @var StockRepository $stockRepository
 */

$this->title = Yii::t('hipanel:stock', 'Model groups');
$this->params['breadcrumbs'][] = $this->title;


?>

<?php $page = IndexPage::begin(['model' => $model, 'dataProvider' => $dataProvider]) ?>
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
        <?= $page->renderSorter(['attributes' => []]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
        <?= ModelGroupGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $model,
            'boxed' => false,
            'columns' => array_merge(['checkbox', 'name', 'descr', ...$stockRepository->getStoredAliases()])
        ]) ?>
        <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>
<?php IndexPage::end() ?>
