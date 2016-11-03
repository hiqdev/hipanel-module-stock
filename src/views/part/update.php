<?php
$this->title = Yii::t('hipanel', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:stock', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_form', [
    'models' => $models,
    'currencyTypes' => reset($models)->scenario === 'update' ? $currencyTypes : null,
]); ?>
