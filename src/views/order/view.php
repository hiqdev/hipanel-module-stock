<?php
/**
 * @var \yii\web\View $this
 */

use hipanel\modules\stock\grid\OrderGridView;
use hipanel\modules\stock\menus\OrderDetailMenu;
use hipanel\widgets\Box;
use hipanel\widgets\MainDetails;
use yii\helpers\Html;

$this->title = Html::encode($model->pageTitle);
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel.stock.order', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
    .profile-block {
        text-align: center;
    }
');
?>
<div class="row">
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-12">
                <?= MainDetails::widget([
                    'title' => $model->pageTitle,
                    'icon' => 'fa-shopping-basket',
                    'subTitle' => Html::a($model->buyer, ['@order/view', 'id' => $model->buyer_id]),
                    'menu' => OrderDetailMenu::widget(['model' => $model], ['linkTemplate' => '<a href="{url}" {linkOptions}><span class="pull-right">{icon}</span>&nbsp;{label}</a>']),
                ]) ?>
            </div>
            <div class="col-md-12">
                <?php
                $box = Box::begin(['renderBody' => false]);
                $box->beginHeader();
                echo $box->renderTitle(Yii::t('hipanel.stock.order', 'Details'));
                $box->endHeader();
                $box->beginBody();
                echo OrderGridView::detailView([
                    'model' => $model,
                    'boxed' => false,
                    'columns' => [
                        'id',
                    ],
                ]);
                $box->endBody();
                $box->end();
                ?>
            </div>
        </div>
    </div>
</div>