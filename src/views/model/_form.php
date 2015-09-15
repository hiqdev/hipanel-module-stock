<?php
use hipanel\helpers\Url;
use hipanel\modules\stock\widgets\combo\ModelProfileCombo;
use hipanel\modules\stock\widgets\combo\UsertagCombo;
use hipanel\widgets\Box;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\JsExpression;

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
        'part',
    ],
]) ?>

    <div class="container-items"><!-- widgetContainer -->
        <?php foreach ($models as $i => $model) : ?>
            <?php
            // necessary for update action.
            if (!$model->isNewRecord) {
                $model->setScenario('update');
                print Html::activeHiddenInput($model, "[$i]id");
            }
            ?>
            <div class="item">
                <?php Box::begin() ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, "[$i]type")->widget(\hiqdev\combo\StaticCombo::className(), [
                                        'type' => 'model/type',
                                        'hasId' => true,
                                        'data' => $types,
                                        'inputOptions' => ['data-field' => 'type']
                                    ]) ?>
                                </div>
                                <!-- /.col-md-6 -->
                                <div class="col-md-6">
                                    <?= $form->field($model, "[$i]brand")->dropDownList($brands) ?>
                                </div>
                                <!-- /.col-md-6 -->
                            </div>
                            <!-- /.row -->
                            <?= $form->field($model, "[$i]model") ?>
                            <?= $form->field($model, "[$i]partno") ?>
                            <?= $form->field($model, "[$i]short") ?>
                            <?= $form->field($model, "[$i]descr") ?>
                            <?= $form->field($model, "[$i]profile")->widget(ModelProfileCombo::className()) ?>
                            <?= $form->field($model, "[$i]tags")->widget(UsertagCombo::className(), [
                                'pluginOptions' => [
                                    'clearWhen' => ['model/type'],
                                ],
                                'filter' => [
                                    'type' => [
                                        'field' => 'model/type',
                                        'format' => new JsExpression('function (id, text, field) {
                                            return "type,model," + id;
                                        }'),
                                    ]
                                ],
                            ]) ?>
                            <?= $form->field($model, "[$i]url") ?>
                        </div>
                        <!-- /.col-md-4 -->
                        <div class="col-md-4"></div>
                        <!-- /.col-md-4 -->
                        <div class="col-md-4"></div>
                        <!-- /.col-md-4 -->
                    </div>
                    <!-- /.row -->
                <?php Box::end() ?>
            </div>
            <!-- /.item -->
        <?php endforeach; ?>
    </div>

<?php DynamicFormWidget::end() ?>
<?php Box::begin(['options' => ['class' => 'box-solid']]) ?>
    <div class="row">
        <div class="col-md-12 no">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-default']) ?>
            &nbsp;
            <?= Html::button(Yii::t('app', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
        </div>
        <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
<?php Box::end() ?>
<?php ActiveForm::end() ?>