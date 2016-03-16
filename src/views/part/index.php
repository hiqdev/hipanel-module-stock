<?php

use hipanel\helpers\Url;
use hipanel\modules\stock\grid\PartGridView;
use hipanel\widgets\ActionBox;
use hipanel\widgets\Pjax;

$this->title = Yii::t('app', 'Parts');
$this->subtitle = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
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
                    'model_type', 'model_brand',
                    'partno', 'serial',
                    'create_time', 'move_time',
                ],
            ]) ?>
            <?= $box->renderPerPage() ?>
            <?= $box->renderRepresentation() ?>
        <?php $box->endActions() ?>
        <?php $box->renderBulkActions([
            'items' => [
                $box->renderBulkButton(Yii::t('app', 'Update'), Url::to('@part/update')),
                $box->renderBulkButton(Yii::t('app', 'Move'), Url::to('@part/bulk-move')),
                $box->renderBulkButton(Yii::t('app', 'Move by one'), Url::to('@part/move')),
                $box->renderBulkButton(Yii::t('app', 'Reserve'), Url::to('@part/reserve')),
                $box->renderBulkButton(Yii::t('app', 'Unreserve'), Url::to('@part/unreserve')),
            //  $box->renderBulkButton(Yii::t('app', 'RMA'), Url::to('@part/rma')),
            ],
        ]) ?>
        <?= $box->renderSearchForm(compact(['types', 'locations', 'brands'])) ?>
    <?php $box->end() ?>
    <?php $box->beginBulkForm() ?>
        <?= PartGridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $model,
            'locations' => $locations,
            'summaryRenderer' => function ($grid) use ($local_sums, $total_sums) {
                if (is_array($total_sums)) {
                    foreach ($total_sums as $cur => $sum) {
                        if ($sum>0) {
                            $totals .= ' &nbsp; <b>' . Yii::$app->formatter->asCurrency($sum, $cur) . '</b>';
                        }
                    }
                }
                if (is_array($local_sums)) {
                    foreach ($local_sums as $cur => $sum) {
                        if ($sum>0) {
                            $locals .= ' &nbsp; <b>' . Yii::$app->formatter->asCurrency($sum, $cur) . '</b>';
                        }
                    }
                }

                return $grid->parentSummary() .
                    ($totals ? Yii::t('app', 'TOTAL') . ':' . $totals : null) .
                    ($locals ? '<br><span class="text-muted">' . Yii::t('app', 'on screen') . ':' . $locals . '</span>' : null)
                ;
            },
            'columns' => $representation=='report' ? [
                'checkbox',
                'model_type', 'model_brand',
                'partno', 'serial',
                'create_date', 'price', 'place',
            ] : [
                'checkbox',
                'main', 'partno', 'serial',
                'last_move', 'move_type_label',
                'move_date', 'order_data', 'DC_ticket_ID',
                'actions',
            ],
        ]) ?>
    <?php $box->endBulkForm() ?>
<?php Pjax::end() ?>
