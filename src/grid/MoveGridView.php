<?php

/*
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\stock\grid;

use hipanel\grid\BoxedGridView;
use hipanel\modules\stock\models\Part;
use hipanel\widgets\ArraySpoiler;
use Yii;
use yii\helpers\Html;

class MoveGridView extends BoxedGridView
{
    public function columns()
    {
        return array_merge(parent::columns(), [
            'date' => [
                'attribute' => 'time',
                'filter'    => false,
                'format'    => 'date',
                'contentOptions' => ['style' => 'white-space:nowrap'],
            ],
            'time' => [
                'filter'    => false,
                'format'    => 'datetime',
                'contentOptions' => ['style' => 'white-space:nowrap'],
            ],
            'move' => [
                'label' => Yii::t('hipanel:stock', 'Move'),
                'format' => 'html',
                'enableSorting' => false,
                'filter' => false,
                'value' => static fn($model) => PartGridView::lastMove($model),
            ],
            'descr' => [
                'format' => 'html',
                'enableSorting' => false,
                'filter' => false,
                'value' => function ($model) {
                    return sprintf('<b>%s</b><br>%s', $model->type_label, $model->getDescription());
                },
            ],
            'data' => [
                'enableSorting' => false,
                'filter' => false,
            ],
            'parts' => [
                'format' => 'raw',
                'filter' => false,
                'enableSorting' => false,
                'value' => function ($model) {
                    /** @var Part $model */
                    return ArraySpoiler::widget([
                        'data' => $model->parts,
                        'visibleCount' => 2,
                        'button' => [
                            'label' => '+' . (count($model->parts) - 2),
                            'popoverOptions' => [
                                'html' => true,
                                'placement' => 'bottom',
                            ],
                        ],
                        'formatter' => function ($item) {
                            return Html::a(sprintf("%s:%s", Html::encode($item['partno']), Html::encode($item['serial'])), ['@part/view', 'id' => $item['part_id']], ['class' => 'text-nowrap']);
                        },
                    ]);
                },
            ],
        ]);
    }
}
