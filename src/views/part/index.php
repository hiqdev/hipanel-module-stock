<?php
use hipanel\modules\stock\grid\PartGridView;
use hipanel\widgets\Pjax;
use hipanel\widgets\ActionBox;

$this->title = Yii::t('app', 'Parts');
$this->subtitle = Yii::t('app', array_filter(Yii::$app->request->get($model->formName(), [])) ? 'filtered list' : 'full list');
$this->breadcrumbs->setItems([
    $this->title,
]); ?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
<?php $box = ActionBox::begin(['model' => $model, 'dataProvider' => $dataProvider]) ?>
<?php $box->beginActions() ?>
<?= $box->renderCreateButton(Yii::t('app', 'Create part')) ?>
<?= $box->renderSearchButton() ?>
<?= $box->renderSorter([
    'attributes' => [
        'id',
        'type',
        'brand',
        'partno',
        'model',
        'serial',
        'time'
    ],
]) ?>
<?= $box->renderPerPage() ?>
<?php $box->endActions() ?>
<?php $box->renderBulkActions([
    'items' => [
        $box->renderBulkButton(Yii::t('app', 'Move'), 'move'),
        $box->renderBulkButton(Yii::t('app', 'Reserve'), 'reserve'),
        $box->renderBulkButton(Yii::t('app', 'Unreserve'), 'un-reserve'),
        $box->renderBulkButton(Yii::t('app', 'RMA'), 'rma'),
    ],
]) ?>
<?= $box->renderSearchForm(compact(['types', 'locations', 'brands'])) ?>
<?php $box->end() ?>
<?php $box->beginBulkForm() ?>
<?= PartGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $model,
    'columns' => [
        'checkbox',
        'main',
        'partno',
        'serial',
        'last_move',
        'move_type_label',

        'move_time',
        'order_data',
        'DC_ticket_ID',

        'actions',
    ],
]) ?>
<?php $box->endBulkForm() ?>
<?php Pjax::end() ?>
