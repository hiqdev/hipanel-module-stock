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
 * @var Part[] $parts
 */

use hipanel\modules\stock\grid\ObjectPartsGridView;
use hipanel\modules\stock\models\Part;
use yii\widgets\Pjax;

?>

<?php Pjax::begin([
    'options' => ['id' => 'configuration_' . mt_rand()],
    'enablePushState' => false,
    'enableReplaceState' => false,
    'scrollTo' => true,
    'timeout' => 999_999,
]) ?>

<?= ObjectPartsGridView::widget(['parts' => $parts]) ?>

<?php Pjax::end() ?>
