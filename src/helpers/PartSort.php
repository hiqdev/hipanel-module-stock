<?php

namespace hipanel\modules\stock\helpers;

use hipanel\modules\stock\models\Model;
use hipanel\modules\stock\models\Part;
use Tuck\Sort\Sort;
use Tuck\Sort\SortChain;

/**
 * Class PartSort
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class PartSort
{
    /**
     * @return SortChain
     */
    public static function byGeneralRules(): SortChain
    {
        return Sort::chain()
            ->asc(self::byModelType())
            ->compare(self::byModelName());
    }

    private static function byModelType()
    {
        return function (Part $part) {
            return ModelSort::byType()(self::toModel($part));
        };
    }

    private static function byModelName()
    {
        return function (Part $a, Part $b) {
            return ModelSort::byName()(self::toModel($a), self::toModel($b));
        };
    }

    private static function toModel(Part $part)
    {
        return new Model([
            'type' => $part->model_type,
            'model' => $part->model_label,
            'brand_label' => $part->model_brand_label,
            'type_label' => $part->model_type_label,
        ]);
    }
}
