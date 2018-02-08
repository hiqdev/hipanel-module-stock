<?php

$this->title = Yii::t('hipanel', 'Copy');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:stock', 'Model groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', compact('models', 'model')) ?>
