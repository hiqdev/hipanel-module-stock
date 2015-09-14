<?php
use hipanel\helpers\Url;
use hipanel\modules\stock\grid\ModelGridView;
use hipanel\widgets\ActionBox;
use hipanel\widgets\Pjax;

$this->title = Yii::t('app', 'Models');
$this->subtitle = Yii::t('app', array_filter(Yii::$app->request->get($model->formName(), [])) ? 'filtered list' : 'full list');
$this->breadcrumbs->setItems([
    $this->title,
]); ?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
<?php $box = ActionBox::begin(['model' => $model, 'dataProvider' => $dataProvider]) ?>
<?php $box->beginActions() ?>
<?= $box->renderSearchButton() ?>
<?= $box->renderSorter([
    'attributes' => [
        'type',
        'brand',
        'model',
    ],
]) ?>
<?= $box->renderPerPage() ?>
<?php $box->endActions() ?>
<?php $box->renderBulkActions([
    'items' => [
        $box->renderBulkButton(Yii::t('app', 'Show for users'), Url::to('@part/un-mark-hidden-from-user')),
        $box->renderBulkButton(Yii::t('app', 'Hide from users'), Url::to('@part/mark-hidden-from-user')),
        $box->renderBulkButton(Yii::t('app', 'Update'), Url::to('@model/update')),
    ],
]) ?>
<?= $box->renderSearchForm(compact(['types', 'brands'])) ?>
<?php $box->end() ?>
<?php $box->beginBulkForm() ?>
<?= ModelGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $model,
    'columns' => [
        'checkbox',
        'type',
        'brand',
        'model',
        'last_prices',
        'actions',
    ],
]) ?>
<?php $box->endBulkForm() ?>
<?php Pjax::end() ?>
