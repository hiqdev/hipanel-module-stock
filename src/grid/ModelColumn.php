<?php declare(strict_types=1);

namespace hipanel\modules\stock\grid;

use DateTimeImmutable;
use hipanel\grid\MainColumn;
use hipanel\modules\stock\models\Part;
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
            $id = implode('-', ['model', $model->id]);
            if (isset($model->warranty_till)) {
                [$warranty, $color] = $this->getWarranty($model);
                $this->grid->view->registerCss("span#$id > a::after {
                    content: '$warranty';
                    background-color: $color;
                    color: white;
                    font-weight: bold;
                    font-size: 12px;
                    margin-left: 5px;
                    padding: 3px 5px;
                    border-radius: 3px;
                    box-shadow: 2px 2px rgba(0, 0, 0, 0.1);
                }");
            }

            return Html::tag('span', $modelLabel, ['id' => $id, 'style' => 'display: inline-block;']);
        };
    }

    private function getWarranty(Part $model): array
    {
        $diffTime = date_diff(new DateTimeImmutable(), new DateTimeImmutable($model->warranty_till));
        $diff = (int)$diffTime->format('%y') * 12 + (int)$diffTime->format('%m');
        $diff = ($diffTime->invert === 1) ? $diff * -1 : $diff;
        $diff = ($diff <= 0) ? 'X' : $diff;
        $color = 'deepskyblue';
        if ($diff <= 6) {
            $color = 'salmon';
        }
        if (!is_numeric($diff)) {
            $color = 'crimson';
        }

        return [$diff, $color];
    }
}
