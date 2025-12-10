<?php declare(strict_types=1);

namespace hipanel\modules\stock\actions;

use hipanel\actions\SmartUpdateAction;
use yii\helpers\ArrayHelper;

class BulkMoveAction extends SmartUpdateAction
{
    public function beforeSave(): void
    {
        $part = $this->controller->request->post('Part');
        $parts = [];
        $partId2srcId = ArrayHelper::remove($part, 'partId2srcId');
        foreach ($partId2srcId as $partId => $srcId) {
            $parts[] = [...$part, 'id' => $partId, 'src_id' => $srcId];
        }
        $this->collection->load($parts);
    }
}
