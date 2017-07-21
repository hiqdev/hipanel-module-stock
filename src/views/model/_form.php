<?php

use hipanel\helpers\Url;
use hipanel\modules\stock\models\Model;
use hipanel\modules\stock\widgets\combo\ModelProfileCombo;
use hipanel\widgets\Box;
use hipanel\widgets\DynamicFormWidget;
use hiqdev\combo\StaticCombo;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

if ($model->isNewRecord || $model->scenario == Model::SCENARIO_COPY) {
    $dynamicUrl = Url::to('@model/subform');
    $this->registerJs(<<< JS
    function getAdditionl(elem) {
        var anchorItem = elem.closest('.item');
        var subFornName = elem.val();
        var itemNumber = elem.attr('id').charAt(6);
        var loadingHtml = '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>';
        anchorItem.find('.l-box').append(loadingHtml);
        anchorItem.find( ".my-dynamic-content" ).load( '{$dynamicUrl}', {'subFormName': subFornName, 'itemNumber': itemNumber}, function (response, status, xhr) {
            anchorItem.find('.overlay').remove();
            if ( status == "error" ) {
                var msg = "Sorry but there was an error";
                console.log(msg);
            }
        });
    }
JS
        , View::POS_READY);
    $this->registerJs('$( document ).on("select2:select", function(event) { getAdditionl($(event.target)) });', View::POS_READY);
}

?>

<?php $form = ActiveForm::begin([
    'id' => 'dynamic-form',
    'action' => $model->scenario === Model::SCENARIO_COPY ? ['@model/create'] : '',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => reset($models)->isNewRecord || reset($models)->scenario === Model::SCENARIO_COPY ? 'create' : 'update']),
]) ?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
    'widgetBody' => '.container-items', // required: css class selector
    'widgetItem' => '.item', // required: css class
    'limit' => 99, // the maximum times, an element can be cloned (default 999)
    'min' => 1, // 0 or 1 (default 1)
    'insertButton' => '.add-item', // css class
    'deleteButton' => '.remove-item', // css class
    'model' => reset($models),
    'formId' => 'dynamic-form',
    'formFields' => [
        'type',
        'brand',
        'profile',
        'model',
        'partno',
        'short',
        'descr',
        'url',
    ],
]) ?>
<div class="container-items"><!-- widgetContainer -->
    <?php foreach ($models as $i => $model) : ?>
        <div class="item">
            <?php Box::begin(['options' => ['class' => 'l-box']]) ?>
            <div class="row">
                <div class="col-lg-offset-10 col-md-offset-10 col-sm-offset-10 col-xs-offset-6 col-sm-2 col-xs-6 text-right">
                    <?php
                    // necessary for update action.
                    if (!$model->isNewRecord && $model->scenario != Model::SCENARIO_COPY) {
                        $model->setScenario('update');
                        echo Html::activeHiddenInput($model, "[$i]id");
                    }
                    ?>
                    <?php if ($model->isNewRecord) : ?>
                        <div class="btn-group">
                            <button type="button" class="add-item btn btn-success btn-sm"><i
                                        class="glyphicon glyphicon-plus"></i></button>
                            <button type="button" class="remove-item btn btn-danger btn-sm"><i
                                        class="glyphicon glyphicon-minus"></i></button>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, "[$i]type")->widget(StaticCombo::class, [
                                'type' => 'model/type',
                                'hasId' => true,
                                'data' => $types,
                                'inputOptions' => array_merge(
                                    ['class' => 'type-element'],
                                    (!$model->isNewRecord && $model->scenario != Model::SCENARIO_COPY) ? ['readonly' => 'readonly'] : []),
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, "[$i]brand")->dropDownList($brands,
                                (!$model->isNewRecord && $model->scenario != Model::SCENARIO_COPY) ? ['disabled' => 'disabled'] : []) ?>
                        </div>
                    </div>
                    <?= $form->field($model, "[$i]profile")->widget(ModelProfileCombo::class) ?>
                    <?= $form->field($model, "[$i]short")->textarea() ?>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, "[$i]model") ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, "[$i]partno") ?>
                        </div>
                    </div>
                    <?= $form->field($model, "[$i]url") ?>
                    <?= $form->field($model, "[$i]descr")->textarea() ?>
                </div>
                <div class="col-md-4 my-dynamic-content">
                    <?php
                    if (!$model->isNewRecord) {
                        $fileName = '_' . $model->type;
                        $path = Yii::getAlias('@hipanel/modules/stock/views/model/' . $fileName . '.php');
                        if (in_array($model->type, $this->context->getCustomType()) && is_file($path)) {
                            echo $this->render('_' . $model->type, ['form' => $form, 'model' => $model, 'i' => (int)$i]);
                        }
                    }
                    ?>
                </div>
            </div>
            <?php Box::end() ?>
        </div>
    <?php endforeach; ?>
</div>

<?php DynamicFormWidget::end() ?>

<div class="row">
    <div class="col-md-12 no">
        <?= Html::submitButton(Yii::t('hipanel', 'Save'), ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
    </div>
</div>
<?php ActiveForm::end() ?>
