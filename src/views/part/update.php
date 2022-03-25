<?php

use hipanel\modules\stock\models\Part;

/**
 * @var Part $model
 * @var array|Part[] $models
 * @var array $currencyTypes
 */

$this->title = Yii::t('hipanel', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:stock', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
    'models' => $models,
    'currencyTypes' => reset($models)->scenario === 'update' ? $currencyTypes : null,
]); ?>
