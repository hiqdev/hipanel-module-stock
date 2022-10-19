<?php

use hipanel\models\IndexPageUiOptions;
use hipanel\modules\stock\grid\OrderGridView;
use hipanel\modules\stock\grid\OrderRepresentations;
use hipanel\modules\stock\models\OrderSearch;
use hipanel\widgets\IndexPage;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var OrderSearch $model
 * @var OrderGridView $gridView
 * @var IndexPageUiOptions $uiModel
 * @var OrderRepresentations $representationCollection
 * @var ActiveDataProvider $dataProvider
 * @var array $types
 */

$this->title = Yii::t('hipanel.stock.order', 'Orders');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

$showFooter = ($uiModel->representation === 'profit-report')
                && (Yii::$app->user->can('order.read-profits'));

?>

<?php $page = IndexPage::begin(['model' => $model, 'dataProvider' => $dataProvider]) ?>

    <?php $page->setSearchFormData(['uiModel' => $uiModel]) ?>
    <?php $page->beginContent('main-actions') ?>
        <?php  if (Yii::$app->user->can('order.create')) : ?>
            <?= Html::a(Yii::t('hipanel.stock.order', 'Create order'), ['@order/create'], ['class' => 'btn btn-sm btn-success']) ?>
        <?php endif; ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('sorter-actions') ?>
        <?= $page->renderSorter([
            'attributes' => [
                'time'
            ],
        ]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('representation-actions') ?>
        <?= $page->renderRepresentations($representationCollection) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
        <?= OrderGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $model,
            'boxed' => false,
            'showFooter' => $showFooter,
            'columns' => $representationCollection->getByName($uiModel->representation)->getColumns(),
        ]) ?>
        <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>
<?php $page->end() ?>
