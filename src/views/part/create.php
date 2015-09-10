<?php

use hipanel\widgets\Box;

$this->title = Yii::t('app', 'Create Part');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', compact(['models', 'moveTypes', 'suppliers', 'currencyTypes'])) ?>

