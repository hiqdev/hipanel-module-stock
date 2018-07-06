<?php

use hipanel\helpers\Url;
use hipanel\models\IndexPageUiOptions;
use hipanel\modules\stock\grid\PartGridLegend;
use hipanel\modules\stock\grid\PartGridView;
use hipanel\modules\stock\widgets\PartLegend;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\IndexPage;
use hipanel\widgets\AjaxModal;
use hipanel\widgets\Pjax;
use yii\bootstrap\Dropdown;
use yii\bootstrap\Modal;
use yii\helpers\Html;

$this->title = Yii::t('hipanel:stock', 'Parts');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

?>


<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>

    <?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>
        <?php $page->setSearchFormData(compact(['types', 'locations', 'brands', 'states'])) ?>

        <?php $page->beginContent('legend') ?>
            <?= GridLegend::widget(['legendItem' => new PartGridLegend($model)]) ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('main-actions') ?>
            <?= Html::a(Yii::t('hipanel', 'Create'), 'create', ['class' => 'btn btn-sm btn-success']) ?>
        <?php $page->endContent() ?>


        <?php $page->beginContent('sorter-actions') ?>
            <?= $page->renderSorter([
                'attributes' => [
                    'id',
                    'model_type', 'model_brand',
                    'partno', 'serial',
                    'create_time', 'move_time',
                ],
            ]) ?>
        <?php $page->endContent() ?>
        <?php $page->beginContent('representation-actions') ?>
            <?= $page->renderRepresentations($representationCollection) ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('bulk-actions') ?>
            <div class="dropdown" style="display: inline-block">
                <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                    <?= Yii::t('hipanel:stock', 'Bulk actions') ?>&nbsp;
                    <span class="caret"></span>
                </button>
                <?= Dropdown::widget([
                    'encodeLabels' => false,
                    'items' => [
                        ['label' => Yii::t('hipanel:stock', 'Repair'), 'url' => '#', 'linkOptions' => ['data-action' => 'repair']],
                        ['label' => Yii::t('hipanel:stock', 'Copy'), 'url' => '#', 'linkOptions' => ['data-action' => 'copy']],
                        ['label' => Yii::t('hipanel:stock', 'Replace'), 'url' => '#', 'linkOptions' => ['data-action' => 'replace']],

                        ['label' => Yii::t('hipanel:stock', 'Reserve'), 'url' => '#', 'linkOptions' => ['data-action' => 'reserve']],
                        ['label' => Yii::t('hipanel:stock', 'Unreserve'), 'url' => '#', 'linkOptions' => ['data-action' => 'unreserve']],
                        ['label' => Yii::t('hipanel:stock', 'RMA'), 'url' => '#', 'linkOptions' => ['data-action' => 'rma']],

                        ['label' => Yii::t('hipanel:stock', 'Update'), 'url' => '#', 'linkOptions' => ['data-action' => 'update']],
                        ['label' => Yii::t('hipanel:stock', 'Update Order No.'), 'url' => '#', 'linkOptions' => ['data-action' => 'update-order-no']],
                        ['label' => Yii::t('hipanel:stock', 'Change model'), 'url' => '#', 'linkOptions' => ['data-action' => 'change-model']],

                        '<li role="presentation" class="divider"></li>',

                        ['label' => Yii::t('hipanel:stock', 'Move by one'), 'url' => '#', 'linkOptions' => ['data-action' => 'move-by-one']],
                        ['label' => Yii::t('hipanel:stock', 'To move'), 'url' => '#', 'linkOptions' => ['data-action' => 'move']],
                        ['label' => Yii::t('hipanel:stock', 'Move by {0}', 2), 'url' => '#', 'linkOptions' => ['data-action' => 'move?groupBy=2']],
                        ['label' => Yii::t('hipanel:stock', 'Move by {0}', 4), 'url' => '#', 'linkOptions' => ['data-action' => 'move?groupBy=4']],
                        ['label' => Yii::t('hipanel:stock', 'Move by {0}', 8), 'url' => '#', 'linkOptions' => ['data-action' => 'move?groupBy=8']],
                        ['label' => Yii::t('hipanel:stock', 'Move by {0}', 16), 'url' => '#', 'linkOptions' => ['data-action' => 'move?groupBy=16']],
                    ],
                ]); ?>
            </div>
            <?php if (Yii::$app->user->can('part.sell')) : ?>
                <?= AjaxModal::widget([
                    'bulkPage' => true,
                    'id' => 'parts-sell',
                    'scenario' => 'sell',
                    'actionUrl' => ['sell'],
                    'handleSubmit' => Url::toRoute('sell'),
                    'size' => Modal::SIZE_LARGE,
                    'header' => Html::tag('h4', Yii::t('hipanel:stock', 'Sell parts'), ['class' => 'modal-title']),
                    'toggleButton' => ['label' => Yii::t('hipanel:stock', 'Sell parts'), 'class' => 'btn btn-default btn-sm'],
                ]) ?>
            <?php endif; ?>
            <?= AjaxModal::widget([
                'bulkPage' => true,
                'id' => 'set-serial-modal',
                'scenario' => 'set-serial',
                'actionUrl' => ['bulk-set-serial'],
                'handleSubmit' => Url::toRoute('set-serial'),
                'size' => Modal::SIZE_LARGE,
                'header' => Html::tag('h4', Yii::t('hipanel:stock', 'Set serial'), ['class' => 'modal-title']),
                'toggleButton' => ['label' => Yii::t('hipanel:stock', 'Set serial'), 'class' => 'btn btn-default btn-sm'],
            ]) ?>
            <?= AjaxModal::widget([
                'bulkPage' => true,
                'id' => 'bulk-set-price-modal',
                'scenario' => 'bulk-set-price',
                'actionUrl' => ['bulk-set-price'],
                'size' => Modal::SIZE_LARGE,
                'header' => Html::tag('h4', Yii::t('hipanel:stock', 'Set price'), ['class' => 'modal-title']),
                'toggleButton' => ['label' => Yii::t('hipanel:stock', 'Set price'), 'class' => 'btn btn-default btn-sm'],
            ]) ?>
            <?php if (Yii::$app->user->can('part.delete')) : ?>
                <?= $page->renderBulkButton('trash', Yii::t('hipanel:stock', 'Trash'), ['color' => 'danger']) ?>
            <?php endif; ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('table') ?>
            <?php $page->beginBulkForm() ?>
            <?= PartGridView::widget([
                'boxed' => false,
                'dataProvider' => $dataProvider,
                'tableOptions' => [
                    'class' => 'table table-striped table-bordered table-condensed'
                ],
                'rowOptions' => function ($model) {
                    return GridLegend::create(new PartGridLegend($model))->gridRowOptions();
                },
                'filterModel' => $model,
                'locations' => $locations,
                'summaryRenderer' => function ($grid, $defaultSummaryCb) use ($local_sums, $total_sums) {
                    $locals = '';
                    $totals = '';
                    if (is_array($total_sums)) {
                        foreach ($total_sums as $cur => $sum) {
                            if ($cur && $sum > 0) {
                                $totals .= ' &nbsp; <b>' . Yii::$app->formatter->asCurrency($sum, $cur) . '</b>';
                            }
                        }
                    }
                    if (is_array($local_sums)) {
                        foreach ($local_sums as $cur => $sum) {
                            if ($cur && $sum > 0) {
                                $locals .= ' &nbsp; <b>' . Yii::$app->formatter->asCurrency($sum, $cur) . '</b>';
                            }
                        }
                    }

                    return $defaultSummaryCb() . '<div class="summary">' .
                        ($totals ? Yii::t('hipanel:stock', 'TOTAL') . ':' . $totals : null) .
                        ($locals ? '<br><span class="text-muted">' . Yii::t('hipanel', 'on screen') . ':' . $locals . '</span>' : null) .
                        '</div>';
                },
                'columns' => $representationCollection->getByName($uiModel->representation)->getColumns(),
            ]) ?>
            <?php $page->endBulkForm() ?>
        <?php $page->endContent() ?>
    <?php $page->end() ?>
<?php Pjax::end() ?>
