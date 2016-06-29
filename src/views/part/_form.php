<?php
use hipanel\modules\stock\widgets\combo\DestinationCombo;
use hipanel\modules\stock\widgets\combo\PartnoCombo;
use hipanel\modules\stock\widgets\combo\SourceCombo;
use hipanel\widgets\AmountWithCurrencyWidget;
use hipanel\widgets\Box;
use hipanel\widgets\DynamicFormWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
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
    'formFields' => [
        'partno',
        'src_id',
        'dst_id',
        'serials',
        'move_type',
        'supplier',
        'order_no',
        'order_no',
        'descr',
        'price',
        'currency',
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
                            <button type="button" class="add-item btn btn-default btn-sm"><i class="glyphicon glyphicon-plus"></i></button>
                            <button type="button" class="remove-item btn btn-default btn-sm"><i class="glyphicon glyphicon-minus"></i></button>
                        </div>
                        <!-- /.btn-group -->
                    <?php endif; ?>
                </div>
                <?php if ($model->isNewRecord) : ?>
                    <div class="col-md-6">
                        <?= $form->field($model, "[$i]partno")->widget(PartnoCombo::className()) ?>
                        <?= $form->field($model, "[$i]src_id")->widget(SourceCombo::className()) ?>
                        <?= $form->field($model, "[$i]dst_id")->widget(DestinationCombo::className()) ?>
                    </div>
                    <!-- /.col-md-6 -->
                    <div class="col-md-6">
                        <?= $form->field($model, "[$i]serials") ?>
                        <div class="row">
                            <div class="col-md-4">
                                <?= $form->field($model, "[$i]move_type")->dropDownList($moveTypes) ?>
                            </div>
                            <!-- /.col-md-4 -->
                            <div class="col-md-4">
                                <?= $form->field($model, "[$i]supplier")->dropDownList($suppliers) ?>
                            </div>
                            <!-- /.col-md-4 -->
                            <div class="col-md-4">
                                <?= $form->field($model, "[$i]order_no") ?>
                            </div>
                            <!-- /.col-md-4 -->
                        </div>
                        <!-- /.row -->
                        <div class="row">
                            <div class="col-md-8">
                                <?= $form->field($model, "[$i]move_descr") ?>
                            </div>
                            <!-- /.col-md-8 -->
                            <div class="col-md-2">
                                <?= $form->field($model, "[$i]price") ?>
                            </div>
                            <!-- /.col-md-2 -->
                            <div class="col-md-2">
                                <?= $form->field($model, "[$i]currency")->dropDownList($model->transformToSymbols(array_keys($currencyTypes))) ?>
                            </div>
                            <!-- /.col-md-2 -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.col-md-6 -->
                <?php else : ?>
                    <div class="col-md-4">
                        <?= $form->field($model, "[$i]partno")->textInput(['readonly' => true]) ?>
                    </div>
                    <!-- /.col-md-4 -->
                    <div class="col-md-4">
                        <?= $form->field($model, "[$i]serial") ?>
                    </div>
                    <!-- /.col-md-4 -->
                    <div class="col-md-4">
                        <?= AmountWithCurrencyWidget::widget([
                            'model' => $model,
                            'inputAttribute' => "[$i]price",
                            'selectAttribute' => "[$i]currency",
                            'selectAttributeOptions' => $currencyTypes,
                        ]) ?>
                    </div>
                    <!-- /.col-md-4 -->
                <?php endif; ?>
            </div>
            <!-- /.row input-row margin-bottom -->
            <?php Box::end() ?>
        </div>
        <!-- /.item -->
    <?php endforeach; ?>
</div>

<?php DynamicFormWidget::end() ?>
<?php Box::begin(['options' => ['class' => 'box-solid']]) ?>
<div class="row">
    <div class="col-md-12 no">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?= Html::button(Yii::t('app', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
    </div>
    <!-- /.col-md-12 -->
</div>
<!-- /.row -->
<?php Box::end() ?>
<?php ActiveForm::end() ?>
