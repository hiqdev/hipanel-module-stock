<?php

namespace hipanel\modules\stock\widgets\combo;

use hipanel\modules\stock\repositories\LocationRepository;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class TrashDestinationDropDownList extends InputWidget
{
    private array $items;

    public function __construct(LocationRepository $disposalRepository, $config = [])
    {
        parent::__construct($config);
        $this->items = $disposalRepository->findForLocation(null, 'trash') + $disposalRepository->findForLocation(null);
    }

    public function run()
    {
        return Html::activeDropDownList($this->model, $this->attribute, $this->items, array_merge($this->options, ['prompt' => '']));
    }
}
