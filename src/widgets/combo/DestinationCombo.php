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

class DestinationCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'stock/dst_name';

    /** {@inheritdoc} */
    public $name = 'name';

    /** {@inheritdoc} */
    public $url = '/stock/move/directions-list';

    /** {@inheritdoc} */
    public $_return = ['id', 'type'];

    /** {@inheritdoc} */
    public $_rename = ['text' => 'name'];

    public $_primaryFilter = 'name_like';

    public function getPluginOptions($options = [])
    {
        return parent::getPluginOptions([
            'select2Options' => [
                'templateSelection' => new JsExpression(/** @lang JavaScript */ "function (data, container) {
                    if ('element' in data) {
                        $(data.element).attr('data-type', data?.type);
                    }

                    return data.text;
                }"),
                'escapeMarkup' => new JsExpression('function (markup) {
                    return markup; // Allows HTML
                }'),
            ],
        ]);
    }
}
