<?php declare(strict_types=1);

namespace hipanel\modules\stock\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class MoveTypeDropDownList extends InputWidget
{
    public array $items = [];

    public function init(): void
    {
        parent::init();
        if (!$this->hasModel()) {
            throw new InvalidConfigException("Model is required");
        }
        $id = ArrayHelper::getValue($this->options, 'id');
        $this->view->registerJs(/** @lang JavaScript */ <<<JS
(($) => {
    "use strict";
    $("#$id").val("");
    const dstSelect = $("#$id").parents(".box").find("select[id*=\"-dst_id\"]");
    if (dstSelect.length) {
      dstSelect.on("change", function () {
        const dstType = $(this).find("option:selected").data("type");
        if (["chwbox", "stock"].includes(dstType)) {
            $("#$id").val("stock");
        } else if ([
          "ahcloud",
          "avdsnode",
          "cloudstorage",
          "delivery",
          "dedicated",
          "system",
          "nic",
          "cdnstat",
          "remote",
          "reserved",
          "suspended",
          "termination",
          "utilization",
          "unused",
          "cdnv2",
          "vdsmaster",
          "cloudservers",
        ].includes(dstType)) {
            $("#$id").val("install");
        } else {
            $("#$id").val("");
        }
      });
    }
})(jQuery);
JS
        );
    }

    public function run()
    {
        return Html::activeDropDownList($this->model, $this->attribute, $this->items, ArrayHelper::merge($this->options, ['prompt' => '']));
    }
}
