<?php
use hipanel\helpers\Url;
use hipanel\modules\stock\widgets\combo\ModelProfileCombo;
use hipanel\modules\stock\widgets\combo\UsertagCombo;
use hipanel\widgets\Box;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\JsExpression;

if (reset($models)->isNewRecord) {
    $dynamicUrl = Url::to('@model/subform');
    $this->registerJs(<<< JS
        // Ajax form by type
        jQuery('[data-field=type]').change(function() {
            var subFornName = jQuery(this).val(), itemNumber = jQuery(this).data('number');
            var loading = '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>';
            jQuery('.l-box').append(loading);
            jQuery( ".my-dynamic-content" ).load( '{$dynamicUrl}', {'subFormName': subFornName, 'itemNumber': itemNumber}, function (response, status, xhr) {
                jQuery('.overlay').remove();
                if ( status == "error" ) {
                    var msg = "Sorry but there was an error";
                    console.log(msg);
                }
            });
        });
JS
    );
}
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
                        <div class="col-lg-offset-10 col-sm-2 text-right">
                            <?php
                            // necessary for update action.
                            if (!$model->isNewRecord) {
                                $model->setScenario('update');
                                print Html::activeHiddenInput($model, "[$i]id");
                            }
                            ?>
                            <?php if ($model->isNewRecord) : ?>
                                <div class="btn-group">
                                    <button type="button" class="add-item btn btn-default btn-sm"><i
                                            class="glyphicon glyphicon-plus"></i></button>
                                    <button type="button" class="remove-item btn btn-default btn-sm"><i
                                            class="glyphicon glyphicon-minus"></i></button>
                                </div>
                                <!-- /.btn-group -->
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, "[$i]type")->widget(\hiqdev\combo\StaticCombo::className(), [
                                        'type' => 'model/type',
                                        'hasId' => true,
                                        'data' => $types,
                                        'inputOptions' => array_merge(
                                            ['data-field' => 'type', 'data-number' => $i],
                                            (!$model->isNewRecord) ? ['readonly' => 'readonly'] : []),
                                    ]) ?>
                                </div>
                                <!-- /.col-md-6 -->
                                <div class="col-md-6">
                                    <?= $form->field($model, "[$i]brand")->dropDownList($brands,
                                        (!$model->isNewRecord) ? ['disabled' => 'disabled'] : []) ?>
                                </div>
                                <!-- /.col-md-6 -->
                            </div>
                            <!-- /.row -->

                            <?= $form->field($model, "[$i]tags")->widget(UsertagCombo::className(), [
                                'pluginOptions' => [
                                    'clearWhen' => ['model/type'],
                                    'select2Options' => [
                                        'multiple' => true,
                                    ],
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
                            <?= $form->field($model, "[$i]profile")->widget(ModelProfileCombo::className()) ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, "[$i]model") ?>
                                </div>
                                <!-- /.col-md-6 -->
                                <div class="col-md-6">
                                    <?= $form->field($model, "[$i]partno") ?>
                                </div>
                                <!-- /.col-md-6 -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.col-md-4 -->
                        <div class="col-md-4">
                            <?= $form->field($model, "[$i]short")->textarea() ?>
                            <?= $form->field($model, "[$i]descr")->textarea() ?>
                            <?= $form->field($model, "[$i]url") ?>
                        </div>
                        <!-- /.col-md-4 -->
                        <div class="col-md-4 my-dynamic-content">
                            <?php if (!$model->isNewRecord) : ?>
                                <?= $this->render('_' . $model->type, ['model' => $model, 'i' => (int)$i]) ?>
                            <?php endif; ?>
                        </div>
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