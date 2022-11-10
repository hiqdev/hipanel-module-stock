<?php

/*
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

/**
 * @var array $data
 */

use hipanel\modules\stock\grid\ObjectPartsGridView;

?>

<?= ObjectPartsGridView::widget(['data' => $data]) ?>
