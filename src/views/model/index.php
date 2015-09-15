<?php
use hipanel\helpers\Url;
use hipanel\modules\stock\grid\ModelGridView;
use hipanel\widgets\ActionBox;
use hipanel\widgets\Pjax;
use yii\bootstrap\Dropdown;

$this->title = Yii::t('app', 'Models');
$this->subtitle = Yii::t('app', array_filter(Yii::$app->request->get($model->formName(), [])) ? 'filtered list' : 'full list');
$this->breadcrumbs->setItems([
    $this->title,
]); ?>

<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>
<?php $box = ActionBox::begin(['model' => $model, 'dataProvider' => $dataProvider]) ?>
<?php $box->beginActions() ?>
<div class="dropdown">
    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        <?= Yii::t('app', 'Create'); ?>
        <span class="caret"></span>
    </button>
    <?= Dropdown::widget([
        'items' => [
            ['label' => Yii::t('app', 'Model'), 'url' => ['@model/create']],
            ['label' => Yii::t('app', 'Server'), 'url' => ['@model/create', 'item' => 'server']],
            ['label' => Yii::t('app', 'Chassis'), 'url' => ['@model/create', 'item' => 'chassis']],
            ['label' => Yii::t('app', 'Motherboard'), 'url' => ['@model/create', 'item' => 'motherboard']],
            ['label' => Yii::t('app', 'RAM'), 'url' => ['@model/create', 'item' => 'ram']],
            ['label' => Yii::t('app', 'HDD'), 'url' => ['@model/create', 'item' => 'hdd']],
            ['label' => Yii::t('app', 'CPU'), 'url' => ['@model/create', 'item' => 'cpu']],
        ]
    ]); ?>
    <?= $box->renderSearchButton() ?>
    <?= $box->renderSorter([
        'attributes' => [
            'type',
            'brand',
            'model',
        ],
    ]) ?>
    <?= $box->renderPerPage() ?>
</div>
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

        'partno',
        'descr',
        'last_prices',
        'actions',
    ],
]) ?>
<?php $box->endBulkForm() ?>
<?php Pjax::end() ?>
