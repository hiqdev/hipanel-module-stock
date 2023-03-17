<?php

/*
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

/**
 * @var Part[] $parts
 */

use hipanel\modules\stock\grid\ObjectPartsGridView;
use hipanel\modules\stock\models\Part;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$groups = ArrayHelper::index($parts, null, 'partno');
$user = Yii::$app->user;

?>

<table class="table table-condensed table-ultra-condensed">

    <caption><?= Yii::t('hipanel:stock', 'Parts') ?></caption>

    <?php foreach ($groups as $partno => $group) : ?>
        <tr>
            <th class="active" style="text-align: right; vertical-align: middle; width: 10%;">
                <?= Yii::t('hipanel:stock', reset($group)->model_type_label) ?>
            </th>
            <th style="vertical-align: middle; width: 15%;">
                <?php $label = $user->can('model.read') ? Html::a($partno, ['@model/view', 'id' => reset($group)->model_id]) : $partno ?>
                <?= count($groups[$partno]) > 1 ? sprintf("<mark>%d</mark><small>x</small> %s", count($groups[$partno]), $label) : $label ?>
            </th>
            <td class="no-padding">
                <?= ObjectPartsGridView::widget(['parts' => $group]) ?>
            </td>
        </tr>
    <?php endforeach ?>

</table>
