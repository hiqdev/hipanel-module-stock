<?php

use common\components\Lang;
use yii\helpers\Html;

$this->title    = Html::encode(sprintf('%s %s %s #%s', Lang::t($model->model_type_label), Lang::t($model->model_brand_label), $model->partno, $model->serial));
$this->subtitle = Yii::t('app','Part detailed information') . ' ' . $this->title;
$this->breadcrumbs->setItems([
    ['label' => Yii::t('app', 'Domains'), 'url' => ['index']],
    $this->title,
]);
?>

<?php \yii\helpers\VarDumper::dump($model, 10, true);?>
