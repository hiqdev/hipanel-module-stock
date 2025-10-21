<?php

use hipanel\modules\stock\models\ModelGroup;

/** @var ModelGroup[] $models */
/** @var ModelGroup $model */


$this->title = Yii::t('hipanel', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:stock', 'Model groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', ['models' => $models , 'model' => $model]) ?>
