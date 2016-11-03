<?php

$this->title = Yii::t('hipanel', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:stock', 'Models'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('_form', compact(['models', 'tyepes', 'brands'])); ?>
