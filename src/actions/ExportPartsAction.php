<?php declare(strict_types=1);

namespace hipanel\modules\stock\actions;

use Generator;
use hipanel\actions\DataExportAction;
use hipanel\modules\stock\models\Part;
use InvalidArgumentException;
use Yii;
use yii\helpers\Html;

class ExportPartsAction extends DataExportAction
{
    protected function getColumns(): array
    {
        return [
            'ID',
            'Type',
            'Manufacturer',
            'Part No.',
            'Serial',
            'Created',
            'Purchase price',
            'Place',
        ];
    }

    protected function generateRows(array $params): Generator
    {
        $ids = $params['ids'] ?? throw new InvalidArgumentException('Parameter "ids" is required');

        yield $this->getColumns();

        $parts = Part::find()
                     ->where(['ids' => $ids])
                     ->limit(-1)
                     ->all();

        foreach ($parts as $part) {
            yield [
                $part->id,
                Html::encode($part->model_type),
                Html::encode($part->model_brand_label),
                Html::encode(
                    sprintf(
                        "%s %s / %s",
                        Yii::t('hipanel:stock', $part->model_type_label),
                        Yii::t('hipanel:stock', $part->model_brand_label),
                        Yii::t('hipanel:stock', $part->model_label),
                    )
                ),
                Html::encode($part->serial),
                $this->formatter->asDate($part->create_time),
                $part->price ? $this->formatter->asCurrency($part->price, $part->currency) : '',
                $part->isTrashed() ? Html::encode($part->place) : sprintf('%s %s', $part->dst_name, $part->place),
            ];
        }
    }
}
