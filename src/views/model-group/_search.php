<?php

use hipanel\modules\stock\widgets\StockAliasSelect;
use hipanel\widgets\AdvancedSearch;

/**
 * @var AdvancedSearch $search
 */

?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('name_like') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('alias_in')->widget(StockAliasSelect::class) ?>
</div>
