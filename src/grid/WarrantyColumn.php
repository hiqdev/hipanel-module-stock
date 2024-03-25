<?php

declare(strict_types=1);

namespace hipanel\modules\stock\grid;

use hipanel\grid\DataColumn;

class WarrantyColumn extends DataColumn
{
    public function init(): void
    {
        parent::init();
        $this->filter = '';
    }
}
