<?php

use common\components\Lang;
use yii\helpers\Html;

$this->title    = Html::encode(sprintf('%s %s %s', $model->type, Lang::t($model->brand_label), $model->model));
$this->subtitle = Yii::t('app','Part detailed information') . ' ' . $this->title;
$this->breadcrumbs->setItems([
    ['label' => Yii::t('app', 'Parts'), 'url' => ['index']],
    $this->title,
]);
?>

<?php \yii\helpers\VarDumper::dump($model, 10, true);?>
