<?php
$this->title = Yii::t('app', 'Bulk Move');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_moveForm', compact(['models', 'types', 'remotehands'])); ?>