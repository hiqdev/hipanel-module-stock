<?php

namespace hipanel\modules\stock\grid;

use hipanel\grid\ActionColumn;
use hipanel\grid\BoxedGridView;
use Yii;
use yii\helpers\Html;

class MoveGridView extends BoxedGridView
{
    public static function defaultColumns()
    {
        return [
            'time' => [
                'filter' => false,
                'format' => ['date', 'php:medium'],
                'sortAttribute' => 'time',
            ],
            'move' => [
                'format' => 'html',
                'enableSorting' => false,
                'filter' => false,
                'value' => function ($model) {
                    return sprintf('%s&nbsp;←&nbsp;%s', $model->dst_name, $model->src_name);
                }
            ],
            'descr' => [
                'format' => 'html',
                'enableSorting' => false,
                'filter' => false,
                'value' => function ($model) {
                    return sprintf('<b>%s</b><br>%s', $model->type_label, $model->descr);
                }
            ],
            'data' => [
                'enableSorting' => false,
                'filter' => false,
            ],
            'parts' => [
                'enableSorting' => false,
                'filter' => false,
                'value' => function ($model) {
                    $out = '';
                    if (is_array($model->parts)) {
                        foreach ($model->parts as $part) {
                            $out .= Html::tag('div', sprintf("%s : %s\n", $part['partno'], $part['serial']));
                        }
                    }
                    return $out;
                }
            ],
            'actions' => [
                'class' => ActionColumn::className(),
                'template' => '{view} {delete}',
                'header' => Yii::t('app', 'Actions'),
            ],
        ];
    }
}
