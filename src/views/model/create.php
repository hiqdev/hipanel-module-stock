<?php

$this->title = Yii::t('hipanel', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel/stock', 'Models'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', compact(['models', 'types', 'brands'])) ?>
