<?php
/**
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\stock\tests\unit\controllers;

use hipanel\modules\stock\controllers\PartController;

class PartControllerTest extends \PHPUnit\Framework\TestCase
{
    protected PartController $object;

    protected function setUp(): void
    {
        $this->object = new PartController('part', null);
    }

    public function testActions(): void
    {
        $this->assertIsArray($this->object->actions());
    }

    public function testBehaviors(): void
    {
        $this->assertIsArray($this->object->behaviors());
    }
}
