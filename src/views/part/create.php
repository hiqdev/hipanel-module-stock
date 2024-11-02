<?php

use hipanel\modules\stock\models\Part;
use yii\web\View;

/**
 * @var Part $model
 * @var Part[] $models
 * @var array $moveTypes
 * @var array $suppliers
 * @var array $currencyTypes
 */

$this->title = Yii::t('hipanel:stock', 'Create Part');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel:stock', 'Parts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs(/** @lang JavaScript */ <<<JS
(() => {
  $(document).on("select2:select", "[id$='order_id']", function (event) {
    const moveDescriptionField = $(event.target).parents(".item").find("[id$='move_descr']");
    if (moveDescriptionField.val().trim() === "") {
        moveDescriptionField.val(event.params.data.text);
    }
  });
})();
JS
    ,
    View::POS_LOAD);

?>

<?= $this->render('_form', [
    'model' => $model,
    'models' => $models,
    'moveTypes' => $moveTypes,
    'suppliers' => $suppliers,
    'currencyTypes' => $currencyTypes,
]) ?>

