<?php

namespace hipanel\modules\stock\widgets\combo;

use hipanel\helpers\ArrayHelper;
use hipanel\modules\stock\models\Part;
use Yii;
use yii\helpers\Json;
use yii\web\JsExpression;

class PartDestinationCombo extends DestinationCombo
{
    /** {@inheritdoc} */
    public $name = 'dst_ids';

    public bool $replaceIdToName = false;

    /** {@inheritdoc} */
    public function getPluginOptions($options = []): array
    {
        return parent::getPluginOptions([
            'select2Options' => [
                'tags' => true,
                'createTag' => new JsExpression("
                    function (params) {
                        // Do not create tag if term has not contains dash symbol
                        if (params.term.includes('-') === false) {
                            return null;
                        }

                        return {
                          id: params.term,
                          text: params.term
                        }
                    }
                "),
            ],
        ]);
    }

    /** {@inheritdoc} */
    public function init()
    {
        parent::init();
        $this->inputOptions['data-destination-field'] = true;
        $spinner = Yii::$app->assetManager->publish(dirname(__DIR__, 2) . '/assets/img/select2-loader.gif')[1];
        $replaceIdToName = Json::htmlEncode($this->replaceIdToName);
        $this->view->registerCss("
        .select2-container--open .select2-selection.select2-loading {
            background-image: url('{$spinner}');
            background-repeat: no-repeat;
            background-position: calc(100% - 25px) 50%;
        }
        ");
        $this->view->registerJs(/** @lang ECMAScript 4 */
            "var tryToResolveDestinationRange = function (event) {
            if (event.params.args.data.id.toString().indexOf('-') !== -1) {
                var Select2 = $(event.target).data('select2');
                var loading = $('.select2-container--open .select2-selection');
                $.ajax({
                    type: 'POST',
                    url: '/stock/part/resolve-destination-range',
                    data: event.params.args.data,
                    beforeSend: function () {
                        loading.toggleClass('select2-loading');
                    },
                    success: function(event, data) {
                        if (data.length) {
                            delete event.params.args.prevented;
                            $(data).each(function(i, datum) {
                                const replaceIdToName = {$replaceIdToName};
                                if (replaceIdToName && datum.id !== datum.text) { 
                                    datum.id = datum.text; 
                                }
                                event.params.args.data = datum;
                                Select2.constructor.__super__.trigger.call(Select2, 'select', event.params.args);
                            });
                        } else {
                            hipanel.notify.error('Nothing found in the specified range');
                        }
                        loading.toggleClass('select2-loading');
                    }.bind(null, event),
                    error: function() {
                        hipanel.notify.error('Failed to assign range!');
                    }
                });
                event.preventDefault();

                return false;
            }
        }
        $('[data-destination-field]').each(function(i, elem) {
            $(elem).on('select2:selecting', tryToResolveDestinationRange);
        });");
    }

    /** {@inheritdoc} */
    public function getFilter()
    {
        return ArrayHelper::merge(parent::getFilter(), [
            'types' => [
                'format' => Part::getDestinationSubTypes(),
            ],
        ]);
    }
}
