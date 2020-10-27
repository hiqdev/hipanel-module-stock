<?php
declare(strict_types=1);

/*
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\stock;

class Module extends \hipanel\base\Module
{
    /**
     * @var array stock names and representative stock labels
     *
     * ```php
     * [
     *    'sdg' => 'SDG',
     * ]
     * ```
     */
    public array $stocksList = [];
}
