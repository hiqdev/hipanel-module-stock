<?php

namespace hipanel\modules\stock\helpers;

use hipanel\modules\stock\models\Model;

/**
 * Class ModelSort
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class ModelSort
{
    public static function byType(): \Closure
    {
        $order = ['SERVER', 'CHASSIS', 'MOTHERBOARD', 'CPU', 'RAM', 'HDD', 'SSD'];

        return function (Model $model) use ($order) {
            $type = mb_strtoupper($model->type);
            if (($key = array_search($type, $order)) !== false) {
                return $key;
            }

            return INF;
        };
    }

    public static function byName(): \Closure
    {
        return function (Model $a, Model $b) {
            return strnatcasecmp($a->name, $b->name);
        };
    }
}
