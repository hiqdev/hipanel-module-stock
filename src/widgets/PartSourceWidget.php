<?php
declare(strict_types=1);

namespace hipanel\modules\stock\widgets;

use hipanel\modules\stock\models\Part;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

final class PartSourceWidget extends Widget
{
    public Part $model;

    public int $index = 0;

    public function run()
    {
        return Html::beginTag('div', ['class' => 'form-group'])
                . Html::tag('label', Yii::t('hipanel:stock', 'Source'), ['for' => 'source-combo'])
                . Html::input('text', null, $this->model->dst_name, [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'source-combo',
                    'style' => 'width: 100%; padding: 6px 12px',
                ])
                . Html::activeHiddenInput($this->model, "[$this->index]src_id", ['value' => $this->model->dst_id])
            . Html::endTag('div');
    }
}
