<?php
/**
 * @var \yii\web\View $this
 * @var \hipanel\modules\stock\models\OrderSearch $model
 * @var \hipanel\modules\stock\grid\OrderGridView $gridView
 * @var \hipanel\models\IndexPageUiOptions $uiModel
 * @var \hipanel\modules\finance\grid\SaleRepresentations $representationCollection
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var array $types
 */

use hipanel\modules\stock\grid\OrderGridView;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use yii\helpers\Html;

$this->title = Yii::t('hipanel.stock.order', 'Orders');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
    <?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>

        <?= $page->setSearchFormData(compact(['types', 'brands', 'states'])) ?>
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

        <?php $page->beginContent('table') ?>
            <?php $page->beginBulkForm() ?>
            <?= OrderGridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $model,
                'boxed' => false,
                'columns' => [
                    'actions',
                    'type',
                    'state',
                    'seller',
                    'buyer',
                    'parts',
                    'comment',
                    'time',
                ],
            ]) ?>
            <?php $page->endBulkForm() ?>
        <?php $page->endContent() ?>
    <?php $page->end() ?>
<?php Pjax::end() ?>

