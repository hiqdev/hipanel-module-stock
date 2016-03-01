<?php

/**
 * @var array $data
 */


use hipanel\base\Lang;
use yii\helpers\Html;
// TODO: design
foreach ($data as $type => $models) {
    echo Html::beginTag('p');
    echo Html::tag('strong' , Yii::t('hipanel/stock/model-types', Lang::lang($type)) . ': ');
    $models_partno = [];
    $serials = [];
    foreach ($models as $model_id => $parts) {
        $modelLink = Html::a(reset($parts)->partno, ['@model/view', 'id' => $model_id]);
        $models_partno[] = (count($parts) > 1 ? count($parts) . 'x' : '') . $modelLink;

        foreach ($parts as $part) {
            $serials[] = Html::a($part->serial, ['@part/view', 'id' => $part->id]);
        }
    }

    echo implode(', ', $models_partno);
    echo ' - ';
    echo implode(', ', $serials);

    echo Html::endTag('p');
}
