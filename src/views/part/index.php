<?php

use hipanel\helpers\Url;
use hipanel\models\IndexPageUiOptions;
use hipanel\modules\stock\grid\PartGridLegend;
use hipanel\modules\stock\grid\PartGridView;
use hipanel\modules\stock\grid\PartRepresentations;
use hipanel\modules\stock\models\PartSearch;
use hipanel\modules\stock\widgets\FastMoveModal;
use hipanel\widgets\AjaxModal;
use hipanel\widgets\AjaxModalWithTemplatedButton;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\IndexPage;
use yii\bootstrap\Dropdown;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var ActiveDataProvider $dataProvider
 * @var IndexPageUiOptions $uiModel
 * @var PartRepresentations $representationCollection
 * @var PartSearch $model
 * @var View $this
 */

$this->title = Yii::t('hipanel:stock', 'Parts');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('(() => {
  const input = document.querySelector("input[name=per-page]");
  const debounce = (func, wait, immediate) => {
    let timeout;

    return function executedFunction() {
      const context = this;
      const args = arguments;

      const later = function () {
        timeout = null;
        if (!immediate) func.apply(context, args);
      };

      const callNow = immediate && !timeout;

      clearTimeout(timeout);

      timeout = setTimeout(later, wait);

      if (callNow) func.apply(context, args);
    };
  };
  const reloadPage = debounce(event => {
    const url = new window.URL(window.location.href);
    url.searchParams.set("per_page", event.target.value);
    window.location = url.href;
  }, 250);
  input.addEventListener("change", reloadPage);
})();');
$insteadPerPageRender = static fn(IndexPage $indexPage): string => Html::input('number', 'per-page', $indexPage->getUiModel()->per_page, [
    'style' => ['display' => 'inline-block', 'width' => '110px'],
    'placeholder' => Yii::t('hipanel', 'Per page'),
    'class' => 'form-control',
    'max' => 999,
    'min' => 1,
]);

$showFooter = ($uiModel->representation === 'profit-report')
                && (Yii::$app->user->can('order.read-profits'));

?>

<?php $page = IndexPage::begin(compact('model', 'dataProvider', 'insteadPerPageRender')) ?>
    <?php $page->setSearchFormData(compact(['types', 'brands', 'states', 'uiModel'])) ?>
    <?php $page->setSearchFormOptions([
        'formOptions' => [
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
            'validateOnType' => true,
            'validationUrl' => Url::toRoute(['validate-search-form', 'scenario' => 'search']),
        ],
    ]) ?>

    <?php $page->beginContent('legend') ?>
        <?= GridLegend::widget(['legendItem' => new PartGridLegend($model)]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('main-actions') ?>
        <?php if (Yii::$app->user->can('part.create')) : ?>
            <?= Html::a(Yii::t('hipanel', 'Create'), 'create', ['class' => 'btn btn-sm btn-success']) ?>
            <?= FastMoveModal::widget() ?>
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
                        AjaxModalWithTemplatedButton::widget([
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
                        ]),
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
                <?php $dropDownItems = array_filter([
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
                            'id' => 'set-real-serials-modal',
                            'bulkPage' => true,
                            'header' => Html::tag('h4', Yii::t('hipanel:stock', 'Set real serials'), ['class' => 'modal-title']),
                            'scenario' => 'set-real-serial',
                            'actionUrl' => ['set-real-serials'],
                            'size' => Modal::SIZE_LARGE,
                            'handleSubmit' => Url::toRoute('set-real-serials'),
                            'toggleButton' => [
                                'tag' => 'a',
                                'label' => Yii::t('hipanel:stock', 'Set real serials'),
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
                        'label' => Yii::t('hipanel:stock', 'Mark as Deleted'),
                        'url' => '#bulk-delete-modal',
                        'linkOptions' => ['data-toggle' => 'modal'],
                        'visible' => Yii::$app->user->can('part.delete'),
                    ],
                    [
                        'label' => Yii::t('hipanel:stock', 'Erase'),
                        'url' => '#bulk-erase-modal',
                        'linkOptions' => ['data-toggle' => 'modal'],
                        'visible' => Yii::$app->user->can('part.erase'),
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
                ])?>
                <?php
                $ajaxModals = [];
                if (Yii::$app->user->can('part.delete')) {
                    $ajaxModals[] = [
                        'id' => 'bulk-delete-modal',
                        'scenario' => 'bulk-delete-modal',
                        'bulkPage' => true,
                        'header' => Html::tag('h4', Yii::t('hipanel:stock', 'Mark as Deleted'), ['class' => 'modal-title']),
                        'headerOptions' => ['class' => 'label-danger'],
                        'handleSubmit' => false,
                        'toggleButton' => false,
                    ];
                }
                if (Yii::$app->user->can('part.erase')) {
                    $ajaxModals[] = [
                        'id' => 'bulk-erase-modal',
                        'scenario' => 'bulk-erase-modal',
                        'bulkPage' => true,
                        'header' => Html::tag('h4', Yii::t('hipanel:stock', 'Erase parts'), ['class' => 'modal-title']),
                        'headerOptions' => ['class' => 'label-danger'],
                        'handleSubmit' => false,
                        'toggleButton' => false,
                    ];
                }
                ?>
                <?= Dropdown::widget([
                    'encodeLabels' => false,
                    'options' => ['class' => 'pull-right'],
                    'items' => $dropDownItems,
                ]) ?>
                <div class="text-left">
                    <?php foreach ($ajaxModals as $ajaxModal) : ?>
                        <?= AjaxModal::widget($ajaxModal) ?>
                    <?php endforeach ?>
                </div>
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
            'showFooter' => $showFooter,
            'columns' => $representationCollection->getByName($uiModel->representation)->getColumns(),
        ]) ?>
        <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>
<?php $page->end() ?>
