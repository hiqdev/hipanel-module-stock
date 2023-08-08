<?php

use hipanel\modules\stock\models\Part;

/**
 * @var array|Part[] $models
 * @var array $currencyTypes
 * @var array $moveTypes
 * @var array $suppliers
 */

$this->title = Yii::t('hipanel:stock', 'Copy Part');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:stock', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('_form', [
    'models' => $models,
    'moveTypes' => $moveTypes,
    'suppliers' => $suppliers,
    'currencyTypes' => $currencyTypes,
  ]) ?>
