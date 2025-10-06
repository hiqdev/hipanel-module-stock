<?php

declare(strict_types=1);


namespace hipanel\modules\stock\widgets\combo;

use hiqdev\combo\Combo;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\JsExpression;

class DestinationCombo extends Combo
{
    public bool $warnIfMovingToStock = false;
    public $type = 'stock/dst_name';
    public $name = 'name';
    public $url = '/stock/move/directions-list';
    public $_return = ['id', 'type'];
    public $_rename = ['text' => 'name'];
    public $_primaryFilter = 'name_like';

    public function init()
    {
        parent::init();

        if ($this->warnIfMovingToStock) {
            $id = ArrayHelper::getValue($this->inputOptions, 'id');
            $alertMessage = Json::htmlEncode(
                Yii::t('hipanel:stock', 'Some of the parts are customer property and probably shouldn\'t be moved to stock.')
            );
            $this->view->registerJs(/** @lang JavaScript */ <<<JS
            (($) => {
              "use strict";
              const dstSelect = $("#$id");
              if (dstSelect.length) {
                dstSelect.on("change", function () {
                  const dstName = $(this).find("option:selected").text();
                  if (dstName.toLowerCase().includes("stock")) {
                    hipanel.notify.error($alertMessage);
                  }
                });
              }
            })(jQuery);
            JS
            );
        }
    }

    public function getPluginOptions($options = [])
    {
        return parent::getPluginOptions([
            'select2Options' => [
                'templateSelection' => new JsExpression(
                /** @lang JavaScript */ "
                function (data, container) {
                    if ('element' in data) {
                        $(data.element).attr('data-type', data?.type);
                    }

                    return data.text;
                }
                "
                ),
                'escapeMarkup' => new JsExpression(
                /** @lang JavaScript */ '
                function (markup) {
                    return markup; // Allows HTML
                }
                '
                ),
            ],
        ]);
    }
}
