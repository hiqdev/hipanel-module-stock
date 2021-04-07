<?php
declare(strict_types=1);

namespace hipanel\modules\stock\widgets;

use hipanel\widgets\FileRender;
use yii\helpers\Html;

class OrderFileRender extends FileRender
{
    public function run(): string
    {
        $extIcon = Html::tag('span', $this->getExtIcon($this->file->type), ['class' => 'pull-right']);

        return Html::a($this->file->filename . $extIcon, $this->getLink(true));
    }
}
