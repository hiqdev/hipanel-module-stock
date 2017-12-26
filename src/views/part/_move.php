<?php

/** @var integer $src_id */
/** @var array $types */
/** @var array $remotehands */

use hipanel\modules\stock\widgets\combo\DestinationCombo;
use hipanel\modules\stock\widgets\combo\SourceCombo;
use hipanel\widgets\Box;
use hipanel\widgets\ArraySpoiler;
use yii\helpers\Html;

?>

<?php Box::begin() ?>
<?php $model = reset($group); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-2">
                    <label><?= Yii::t('hipanel:stock', 'Parts in move:') ?></label>
                    <div class="well well-sm">
                        <?= ArraySpoiler::widget([
                            'data' => $group,
                            'visibleCount' => count($group),
                            'formatter' => function ($model) {
                                return $model->partno . sprintf(' (%s)', $model->serial);
                            },
                            'delimiter' => ',&nbsp; ',
                        ]); ?>
                        <div>
                            <?php foreach ($group as $model) : ?>
                                <?= Html::activeHiddenInput($model, "[$src_id]id[]", ['value' => $model->id]); ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-10">
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, "[$src_id]src_id")->widget(SourceCombo::class, [
                                'inputOptions' => [
                                    'id' => "$src_id-src_id-" . uniqid(),
                                    'readonly' => true,
                                    'unselect' => $model->src_id,
                                ],
                            ]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, "[$src_id]dst_id")->widget(DestinationCombo::class, [
                                'inputOptions' => [
                                    'id' => "$src_id-dst_id-" . uniqid(),
                                ],
                            ]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, "[$src_id]type")->dropDownList($types, ['id' => "$src_id-type-" . uniqid()]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, "[$src_id]remotehands")->dropDownList($remotehands, ['id' => "$src_id-remotehands-" . uniqid()]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, "[$src_id]remote_ticket")->textInput(['id' => "$src_id-remote_ticket-" . uniqid()]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, "[$src_id]hm_ticket")->textInput(['id' => "$src_id-hm_ticket-" . uniqid()]) ?>
                        </div>
                    </div>
                    <?= $form->field($model, "[$src_id]descr")->textarea(['id' => "$src_id-descr-" . uniqid()]) ?>
                </div>
            </div>
        </div>
    </div>
<?php Box::end() ?>