<?php

use hipanel\modules\stock\models\Part;
use hipanel\modules\stock\widgets\combo\CompanyCombo;
use hipanel\modules\stock\widgets\combo\PartDestinationCombo;
use hipanel\modules\stock\widgets\combo\ModelCombo;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hipanel\modules\stock\widgets\combo\SourceCombo;
use hipanel\widgets\AmountWithCurrency;
use hipanel\widgets\Box;
use hipanel\widgets\DateTimePicker;
use hipanel\widgets\DynamicFormCopyButton;
use hipanel\widgets\DynamicFormWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var Part $model
 * @var array $currencyTypes
 */

$this->registerJs(/** @lang JavaScript */ <<<JS
(() => {
  $(document).on("select2:select", "[id$='partno']", function (event) {
    const datetimePlugin = $(event.target).parents(".item").find("[id$='warranty_till']").parent().data('datetimepicker');
    const { warranty_months } = event.params.data;
    if (warranty_months) {
      datetimePlugin.setDate(moment().add(warranty_months, 'months').toDate());
    }
  });
})();
JS
    ,
    View::POS_LOAD);

?>
<?php $form = ActiveForm::begin([
    'id' => 'dynamic-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => reset($models)->isNewRecord ? 'create' : 'update']),
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
    'formFields' => $model->isNewRecord ? [
        'partno',
        'src_id',
        'dst_id',
        'dst_ids',
        'serials',
        'move_type',
        'descr',
        'price',
        'currency',
        'company_id',
        'warranty_till',
    ] : [
        'model_id',
        'dst_name',
        'serial',
        'company_id',
        'price',
        'currency',
        'warranty_till',
    ],
]) ?>

<div class="container-items"><!-- widgetContainer -->
    <?php foreach ($models as $i => $model) : ?>
        <?php
        // necessary for update action.
        if ($model->scenario == 'update') {
            $model->setScenario('update');
            echo Html::activeHiddenInput($model, "[$i]id");
        }
        ?>
        <div class="item">
            <?php Box::begin() ?>
            <div class="row input-row margin-bottom">
                <div class="col-lg-offset-10 col-sm-2 text-right">
                    <?php if ($model->isNewRecord) : ?>
                        <div class="btn-group">
                            <button type="button" class="add-item btn btn-success btn-sm"><i
                                        class="glyphicon glyphicon-plus"></i></button>
                            <?= DynamicFormCopyButton::widget() ?>
                            <button type="button" class="remove-item btn btn-danger btn-sm"><i
                                        class="glyphicon glyphicon-minus"></i></button>
                        </div>
                    <?php endif ?>
                </div>
                <?php if ($model->isNewRecord) : ?>
                    <div class="col-md-6">
                        <?= $form->field($model, "[$i]partno")->widget(PartnoCombo::class) ?>
                        <?= $form->field($model, "[$i]src_id")->widget(SourceCombo::class) ?>
                        <?php if ($model->dst_id) : ?>
                            <?= $form->field($model, "[$i]dst_id")->widget(PartDestinationCombo::class, ['name' => 'dst_id']) ?>
                        <?php else : ?>
                            <?= $form->field($model,
                                "[$i]dst_ids",
                                ['options' => ['class' => 'required']])->widget(PartDestinationCombo::class, [
                                'primaryFilter' => 'name_inilike',
                                'hasId' => true,
                                'multiple' => true,
                            ]) ?>
                        <?php endif; ?>
                        <?= $form->field($model, "[$i]warranty_till")->widget(DateTimePicker::class, [
                            'clientOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'minView' => 2,
                                'todayHighlight' => true,
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($model, "[$i]serials")->hint(Yii::t('hipanel:stock',
                                    'In order to use the automatic serials generation, the field should look like: <samp>[number of generated serials]_</samp>')) ?>
                            </div>
                            <div class="col-md-12">
                                <?= $form->field($model, "[$i]move_descr") ?>
                            </div>
                            <div class="col-md-6 <?= AmountWithCurrency::$widgetClass ?>">
                                <?= $form->field($model, "[$i]price")->widget(AmountWithCurrency::class, [
                                    'currencyAttributeName' => "[$i]currency",
                                    'currencyAttributeOptions' => [
                                        'items' => $this->context->getCurrencyTypes(),
                                    ],
                                ]) ?>
                                <?= $form->field($model,
                                    "[$i]currency",
                                    ['template' => '{input}{error}'])->hiddenInput(['data-amount-with-currency' => 'currency']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, "[$i]company_id")->widget(CompanyCombo::class) ?>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, "[$i]model_id")->widget(ModelCombo::class) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, "[$i]dst_name")->textInput(['disabled' => true])->label(Yii::t('hipanel:stock',
                                    'Location')) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, "[$i]serial") ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <?= $form->field($model, "[$i]warranty_till")->widget(DateTimePicker::class, [
                                    'clientOptions' => [
                                        'format' => 'yyyy-mm-dd',
                                        'minView' => 2,
                                        'todayHighlight' => true,
                                    ],
                                ]) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $form->field($model, "[$i]company_id")->widget(CompanyCombo::class) ?>
                            </div>
                            <div class="col-md-6 <?= AmountWithCurrency::$widgetClass ?>">
                                <?= $form->field($model, "[$i]price")->widget(AmountWithCurrency::class, [
                                    'currencyAttributeName' => "[$i]currency",
                                    'currencyAttributeOptions' => [
                                        'items' => $currencyTypes,
                                    ],
                                ]) ?>
                                <?= $form->field($model, "[$i]currency", ['template' => '{input}{error}'])->hiddenInput() ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, "[$i]move_descr") ?>
                    </div>
                <?php endif ?>
            </div>
            <?php Box::end() ?>
        </div>
    <?php endforeach ?>
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
