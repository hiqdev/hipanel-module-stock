<?php
use hipanel\helpers\Url;
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
        $box->renderBulkButton(Yii::t('app', 'Update'), Url::to('@part/update')),
        $box->renderBulkButton(Yii::t('app', 'Move'), Url::to('@part/bulk-move')),
        $box->renderBulkButton(Yii::t('app', 'To move by one'), Url::to('@part/move')),
        $box->renderBulkButton(Yii::t('app', 'Reserve'), Url::to('@part/reserve')),
        $box->renderBulkButton(Yii::t('app', 'Unreserve'), Url::to('@part/unreserve')),
//        $box->renderBulkButton(Yii::t('app', 'RMA'), Url::to('@part/rma')),
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

        'move_date',
        'order_data',
        'DC_ticket_ID',

        'actions',
    ],
]) ?>
<?php $box->endBulkForm() ?>
<?php Pjax::end() ?>
