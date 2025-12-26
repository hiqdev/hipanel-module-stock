<?php
/**
 * Client module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-client
 * @package   hipanel-module-client
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

use hipanel\widgets\BulkOperation;

echo BulkOperation::widget([
    'model' => $model,
    'models' => $models,
    'scenario' => 'erase',
    'affectedObjects' => Yii::t('hipanel:stock', 'Affected parts'),
    'formatterField' => 'title',
    'hiddenInputs' => ['id', 'title'],
    'bodyWarning' => Yii::t('hipanel:stock', 'This action is irreversible and causes full data loss including move history!'),
    'submitButton' => Yii::t('hipanel', 'Erase'),
    'submitButtonOptions' => ['class' => 'btn btn-danger'],
]);
