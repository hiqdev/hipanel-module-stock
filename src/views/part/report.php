<?php

use hipanel\helpers\Url;
use hipanel\modules\stock\grid\PartGridView;
use hipanel\widgets\ActionBox;
use hipanel\widgets\Pjax;

$this->title = Yii::t('app', 'Parts');
$this->subtitle = Yii::t('app', 'Report');
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
                    'model_type',
                    'model_brand',
                    'partno',
                    'serial',
                    'create_time',
                    'move_time',
                ],
            ]) ?>
            <?= $box->renderPerPage() ?>
        <?php $box->endActions() ?>
    <?php $box->end() ?>
    <?php $box->beginBulkForm() ?>
        <?= PartGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $model,
            'columns' => [
                'checkbox',
                'model_type', 'model_brand',
                'partno',
                'serial',
                'create_date',
                'price',
                'place',
            ],
        ]) ?>
    <?php $box->endBulkForm() ?>
<?php Pjax::end() ?>
