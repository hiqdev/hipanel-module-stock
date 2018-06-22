<?php

namespace hipanel\modules\stock\helpers;

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
            return ModelSort::byType()($part->model);
        };
    }

    private static function byModelName()
    {
        return function (Part $a, Part $b) {
            return ModelSort::byName()($a->model, $b->model);
        };
    }
}
