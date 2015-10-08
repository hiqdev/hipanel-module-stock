<?php
use hipanel\modules\stock\grid\MoveGridView;
use hipanel\widgets\ActionBox;
use hipanel\widgets\Pjax;

$this->title = Yii::t('app', 'Moves');
$this->subtitle = Yii::t('app', array_filter(Yii::$app->request->get($model->formName(), [])) ? 'filtered list' : 'full list');
$this->breadcrumbs->setItems([
    $this->title,
]);
?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
<?php $box = ActionBox::begin(['model' => $model, 'dataProvider' => $dataProvider]) ?>
<?php $box->beginActions() ?>
<?= $box->renderSearchButton() ?>
<?= $box->renderSorter([
    'attributes' => [
        'time',
        'client'
    ],
]) ?>
<?= $box->renderPerPage() ?>
<?php $box->endActions() ?>
<?php $box->renderBulkActions([
    'items' => [
        $box->renderDeleteButton(Yii::t('app', 'Delete')), // , Url::to('@move/delete')
    ],
]) ?>
<?= $box->renderSearchForm(compact(['types'])) ?>
<?php $box->end() ?>
<?php $box->beginBulkForm() ?>
<?= MoveGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $model,
    'columns' => [
        'checkbox',
        'client',
        'date',
        'move',
        'descr',
        'data',
        'parts',
        'actions',
    ],
]) ?>
<?php $box->endBulkForm() ?>
<?php Pjax::end() ?>
