<?php
/**
 * @var \yii\web\View $this
 */

$this->title = Yii::t('hipanel.stock.order', 'Create order');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel.stock.order', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', compact(['model', 'models'])) ?>
