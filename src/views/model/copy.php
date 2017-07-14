<?php

$this->title = Yii::t('hipanel:stock', 'Copy');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:stock', 'Models'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('_form', compact(['model', 'models', 'types', 'brands'])); ?>
