<?php

/*
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\stock\widgets\combo;

use hiqdev\combo\Combo;
use yii\web\JsExpression;

class LocationsCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'part/place';

    /** {@inheritdoc} */
    public $name = 'place';

    /** {@inheritdoc} */
    public $url = '/stock/part/locations-list';

    /** {@inheritdoc} */
    public $_return = ['id', 'place', 'count'];

    /** {@inheritdoc} */
    public $_rename = ['text' => 'place'];

    public $_primaryFilter = 'place';

    /** {@inheritdoc} */
    public function getPluginOptions($options = [])
    {
        return parent::getPluginOptions([
            'select2Options' => [
                'minimumResultsForSearch' => 100,
                'templateResult' => new JsExpression("data => {
                    if (data.loading) {
                        return data.text;
                    }
                    const place = '<b>' + data.place + '</b>&nbsp;&nbsp;';
                    const count = '<span class=\"text-muted\">(' + data.count + ')</span>';
                    
                    return place + count;
                }"),
                'escapeMarkup' => new JsExpression('function (markup) {
                    return markup; // Allows HTML
                }'),
            ],
        ]);
    }
}
