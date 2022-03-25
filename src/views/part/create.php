<?php

use hipanel\modules\stock\models\Part;

/**
 * @var Part $model
 * @var array|Part[] $models
 */

$this->title = Yii::t('hipanel:stock', 'Create Part');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:stock', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', compact(['model', 'models', 'moveTypes', 'suppliers', 'currencyTypes'])) ?>

