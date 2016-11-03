<?php

$this->title = Yii::t('hipanel:stock', 'Copy Part');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:stock', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('_form', compact(['models', 'moveTypes', 'suppliers', 'currencyTypes'])) ?>
