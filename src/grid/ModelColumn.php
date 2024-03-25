<?php

declare(strict_types=1);

namespace hipanel\modules\stock\grid;

use DateTimeImmutable;
use hipanel\grid\MainColumn;
use hipanel\modules\stock\models\Part;
use hipanel\widgets\Label;
use yii\helpers\Html;
use Yii;

class ModelColumn extends MainColumn
{
    public function init(): void
    {
        parent::init();
        $this->value = function (Part $model): string {
            $modelLabel = Html::encode($model->model_label);
            if (Yii::$app->user->can('model.read')) {
                $modelLabel = Html::a($modelLabel, ['@model/view', 'id' => $model->model_id]);
            }
            if (isset($model->warranty_till)) {
                $modelLabel .= $this->getWarrantyLabel($model);
            }

            return $modelLabel;
        };
    }

    private function getWarrantyLabel(Part $model): string
    {
        $diffTime = date_diff(new DateTimeImmutable(), new DateTimeImmutable($model->warranty_till));
        $diff = (int)$diffTime->format('%y') * 12 + (int)$diffTime->format('%m');
        $diff = ($diffTime->invert === 1) ? $diff * -1 : $diff;
        $diff = ($diff <= 0) ? 'X' : $diff;
        $color = 'info';
        if ($diff <= 6) {
            $color = 'warning';
        }
        if (!is_numeric($diff)) {
            $color = 'danger';
        }

        return Label::widget(['label' => $diff, 'tag' => 'sup', 'color' => $color]);
    }
}
