<?php
$this->title = Yii::t('app', 'Copy Part');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', compact(['models', 'moveTypes', 'suppliers', 'currencyTypes'])) ?>
