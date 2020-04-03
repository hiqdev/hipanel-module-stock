<?php

use hipanel\helpers\Url;
use hipanel\modules\stock\grid\PartGridLegend;
use hipanel\modules\stock\grid\PartGridView;
use hipanel\widgets\AjaxModalWithTemplatedButton;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\IndexPage;
use hipanel\widgets\Pjax;
use hipanel\widgets\SummaryWidget;
use yii\bootstrap\Dropdown;
use yii\bootstrap\Modal;
use yii\helpers\Html;

/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \hipanel\models\IndexPageUiOptions $uiModel
 * @var \hipanel\modules\stock\grid\PartRepresentations $representationCollection
 * @var string[] $locations
 * @var float[] $local_sums
 * @var float[] $total_sums
 * @var \hipanel\modules\stock\models\PartSearch $model
 * @var \yii\web\View $this
 */

$this->title = Yii::t('hipanel:stock', 'Parts');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

$showFooter = ($uiModel->representation === 'profit-report')
                && (Yii::$app->user->can('order.read-profits'));

?>


<?php Pjax::begin(array_merge(Yii::$app->params['pjax'], ['enablePushState' => true])) ?>

    <?php $page = IndexPage::begin(compact('model', 'dataProvider')) ?>
        <?php $page->setSearchFormData(compact(['types', 'locations', 'brands', 'states', 'uiModel'])) ?>

        <?php $page->beginContent('legend') ?>
            <?= GridLegend::widget(['legendItem' => new PartGridLegend($model)]) ?>
        <?php $page->endContent() ?>

        <?php $page->beginContent('main-actions') ?>
            <?php if (Yii::$app->user->can('part.create')) : ?>
                <?= Html::a(Yii::t('hipanel', 'Create'), 'create', ['class' => 'btn btn-sm btn-success']) ?>
            <?php endif ?>
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

            <?= $page->withPermission('move.create', $page->renderBulkButton('rma', Yii::t('hipanel:stock', 'RMA'))) ?>
            <?= $page->withPermission('move.create', $page->renderBulkButton('move', Yii::t('hipanel:stock', 'To move'))) ?>
            <?php if (Yii::$app->user->can('part.sell')) : ?>
                <div class="dropdown" style="display: inline-block">
                    <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                        <?= Yii::t('hipanel:stock', 'Sell parts') ?>&nbsp;
                        <span class="caret"></span>
                    </button>
                    <?= Dropdown::widget([
                        'encodeLabels' => false,
                        'items' => array_filter([
                            AjaxModalWithTemplatedButton::widget([
                                'ajaxModalOptions' => [
                                    'bulkPage' => true,
                                    'usePost' => true,
                                    'id' => 'parts-sell',
                                    'scenario' => 'sell',
                                    'actionUrl' => ['sell'],
                                    'handleSubmit' => Url::toRoute('sell'),
                                    'size' => Modal::SIZE_LARGE,
                                    'header' => Html::tag('h4', Yii::t('hipanel:stock', 'Sell parts'), ['class' => 'modal-title']),
                                    'toggleButton' => [
                                        'tag' => 'a',
                                        'label' => Yii::t('hipanel:stock', 'Sell parts'),
                                        'class' => 'clickable',
                                    ],
                                ],
                                'toggleButtonTemplate' => '<li>{toggleButton}</li>',
                            ]),
                            Yii::$app->user->can('test.alpha') ? AjaxModalWithTemplatedButton::widget([
                                'ajaxModalOptions' => [
                                    'bulkPage' => true,
                                    'usePost' => true,
                                    'id' => 'parts-sell-by-plan',
                                    'scenario' => 'sell',
                                    'actionUrl' => ['sell-by-plan'],
                                    'handleSubmit' => Url::toRoute('sell-by-plan'),
                                    'size' => Modal::SIZE_LARGE,
                                    'header' => Html::tag('h4', Yii::t('hipanel:stock', 'Sell parts by tariff plan'), ['class' => 'modal-title']),
                                    'toggleButton' => [
                                        'tag' => 'a',
                                        'label' => Yii::t('hipanel:stock', 'Sell parts by tariff plan'),
                                        'class' => 'clickable',
                                    ],
                                ],
                                'toggleButtonTemplate' => '<li>{toggleButton}</li>',
                            ]) : null,
                        ])
                    ]) ?>
                </div>
            <?php endif ?>

            <?php if (Yii::$app->user->can('part.create') || Yii::$app->user->can('part.update') || Yii::$app->user->can('move.create')) : ?>
                <div class="dropdown" style="display: inline-block">
                    <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                        <?= Yii::t('hipanel:stock', 'Bulk actions') ?>&nbsp;
                        <span class="caret"></span>
                    </button>
                    <?= Dropdown::widget([
                        'encodeLabels' => false,
                        'items' => array_filter([
                            Yii::$app->user->can('part.update') ? AjaxModalWithTemplatedButton::widget([
                                'ajaxModalOptions' => [
                                    'id' => 'set-serial-modal',
                                    'bulkPage' => true,
                                    'header' => Html::tag('h4', Yii::t('hipanel:stock', 'Set serial'), ['class' => 'modal-title']),
                                    'scenario' => 'set-serial',
                                    'actionUrl' => ['bulk-set-serial'],
                                    'size' => Modal::SIZE_LARGE,
                                    'handleSubmit' => Url::toRoute('set-serial'),
                                    'toggleButton' => [
                                        'tag' => 'a',
                                        'label' => Yii::t('hipanel:stock', 'Set serial'),
                                    ],
                                ],
                                'toggleButtonTemplate' => '<li>{toggleButton}</li>',
                            ]) : null,
                            Yii::$app->user->can('part.update') ? AjaxModalWithTemplatedButton::widget([
                                'ajaxModalOptions' => [
                                    'id' => 'bulk-set-price-modal',
                                    'bulkPage' => true,
                                    'header' => Html::tag('h4', Yii::t('hipanel:stock', 'Set price'), ['class' => 'modal-title']),
                                    'scenario' => 'bulk-set-price',
                                    'toggleButton' => [
                                        'tag' => 'a',
                                        'label' => Yii::t('hipanel:stock', 'Set price'),
                                    ],
                                ],
                                'toggleButtonTemplate' => '<li>{toggleButton}</li>',
                            ]) : null,
                            [
                                'label' => Yii::t('hipanel:stock', 'Repair'),
                                'url' => '#',
                                'linkOptions' => [
                                    'data-action' => 'repair',
                                ],
                                'visible' => Yii::$app->user->can('move.create'),
                            ],
                            [
                                'label' => Yii::t('hipanel:stock', 'Copy'),
                                'url' => '#',
                                'linkOptions' => [
                                    'data-action' => 'copy',
                                ],
                                'visible' => Yii::$app->user->can('part.create'),
                            ],
                            [
                                'label' => Yii::t('hipanel:stock', 'Replace'),
                                'url' => '#',
                                'linkOptions' => [
                                    'data-action' => 'replace',
                                ],
                                'visible' => Yii::$app->user->can('move.create'),
                            ],

                            [
                                'label' => Yii::t('hipanel:stock', 'Reserve'),
                                'url' => '#',
                                'linkOptions' => [
                                    'data-action' => 'reserve',
                                ],
                                'visible' => Yii::$app->user->can('part.update'),
                            ],
                            [
                                'label' => Yii::t('hipanel:stock', 'Unreserve'),
                                'url' => '#',
                                'linkOptions' => [
                                    'data-action' => 'unreserve',
                                ],
                                'visible' => Yii::$app->user->can('part.update'),
                            ],
                            [
                                'label' => Yii::t('hipanel:stock', 'Update'),
                                'url' => '#',
                                'linkOptions' => [
                                    'data-action' => 'update',
                                ],
                                'visible' => Yii::$app->user->can('part.update'),
                            ],
                            [
                                'label' => Yii::t('hipanel:stock', 'Update Order No.'),
                                'url' => '#',
                                'linkOptions' => [
                                    'data-action' => 'update-order-no',
                                ],
                                'visible' => Yii::$app->user->can('part.update'),
                            ],
                            [
                                'label' => Yii::t('hipanel:stock', 'Change model'),
                                'url' => '#',
                                'linkOptions' => [
                                    'data-action' => 'change-model',
                                ],
                                'visible' => Yii::$app->user->can('part.update'),
                            ],
                            '<li role="presentation" class="divider"></li>',
                            [
                                'label' => Yii::t('hipanel:stock', 'Move by one'),
                                'url' => '#',
                                'linkOptions' => [
                                    'data-action' => 'move-by-one',
                                ],
                                'visible' => Yii::$app->user->can('move.create'),
                            ],
                            [
                                'label' => Yii::t('hipanel:stock', 'Move by {0}', 2),
                                'url' => '#',
                                'linkOptions' => [
                                    'data-action' => 'move?groupBy=2',
                                ],
                                'visible' => Yii::$app->user->can('move.create'),
                            ],
                            [
                                'label' => Yii::t('hipanel:stock', 'Move by {0}', 4),
                                'url' => '#',
                                'linkOptions' => [
                                    'data-action' => 'move?groupBy=4',
                                ],
                                'visible' => Yii::$app->user->can('move.create'),
                            ],
                            [
                                'label' => Yii::t('hipanel:stock', 'Move by {0}', 8),
                                'url' => '#',
                                'linkOptions' => [
                                    'data-action' => 'move?groupBy=8',
                                ],
                                'visible' => Yii::$app->user->can('move.create'),
                            ],
                            [
                                'label' => Yii::t('hipanel:stock', 'Move by {0}', 16),
                                'url' => '#',
                                'linkOptions' => [
                                    'data-action' => 'move?groupBy=16',
                                ],
                                'visible' => Yii::$app->user->can('move.create'),
                            ],
                        ]),
                    ]) ?>
                </div>
            <?php endif ?>
            <?php if (Yii::$app->user->can('part.delete')) : ?>
                <?= $page->renderBulkButton('trash', Yii::t('hipanel:stock', 'Trash'), ['color' => 'danger']) ?>
            <?php endif ?>
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
                    return $defaultSummaryCb() . SummaryWidget::widget([
                        'local_sums' => $local_sums,
                        'total_sums' => $total_sums,
                    ]);
                },
                'showFooter' => $showFooter,
                'columns' => $representationCollection->getByName($uiModel->representation)->getColumns(),
            ]) ?>
            <?php $page->endBulkForm() ?>
        <?php $page->endContent() ?>
    <?php $page->end() ?>
<?php Pjax::end() ?>
