<?php

declare(strict_types=1);

namespace hipanel\modules\stock\widgets;

use hipanel\components\SettingsStorage;
use hipanel\helpers\StringHelper;
use hipanel\modules\stock\helpers\StockLocationsProvider;
use hipanel\widgets\HookTrait;
use hipanel\widgets\VueTreeSelectInput;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class StockLocationsListTreeSelect extends VueTreeSelectInput
{
    use HookTrait;

    public $name = 'stocks';

    /**
     * @var bool Whether to save selected locations to the storage
     * When there is no underlying model, we can only save locations to the global storage.
     */
    public bool $useStorage = true;

    public function __construct(
        private readonly SettingsStorage $storage,
        private readonly StockLocationsProvider $provider,
        $config = []
    ) {
        parent::__construct($config);
    }

    /**
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        $this->useStorage = !$this->hasModel();
        $this->value = $this->hasModel()
            ? StringHelper::explode($this->model->{$this->attribute} ?? '')
            : $this->provider->getLocations();

        parent::init();
        $this->registerVueContainer();
    }

    public function run()
    {
        $options = [
            'v-model' => 'value',
            'data' => [
                'value' => $this->value,
                'options' => Json::encode($this->buildOptions()),
                'save-on-change' => $this->useStorage ? 1 : 0,
            ],
        ];

        if ($this->hasModel()) {
            $activeInput = Html::activeHiddenInput($this->model, $this->attribute, $options);
        } else {
            $activeInput = Html::hiddenInput($this->name, null, $options);
        }

        $this->view->registerCss(sprintf('
            .vue-treeselect__option,
            .vue-treeselect__label-container {
                width: auto;
            }
            #%s {
                display: flex;
                flex-direction: column;
                gap: .3rem
            }
            [v-cloak] {
                display: none;
            }
        ', $this->getId()));

        return sprintf(
            /** @lang HTML */
            '
            <div id="%s" style="margin-bottom: 1em;">
                <treeselect
                  :options="options"
                  :show-count="true"
                  :always-open="false"
                  :append-to-body="true"
                  :disable-branch-nodes="false"
                  :multiple="true"
                  value-consists-of="BRANCH_PRIORITY"
                  delimiter=","
                  :auto-select-ancestors="true"
                  :clearable="false"
                  :allow-selecting-disabled-descendants="true"
                  search-nested
                  placeholder="%s"
                  v-model="value"
                  z-index="1100"
                  @input="onChange"
                  @close="updateColumns"
                >
                    <div slot="value-label" slot-scope="{ node }" v-html="node.raw.label"></div>
                </treeselect>
                %s
                <button class="btn btn-xs btn-danger btn-flat" v-cloak v-show="locationsInQueryParams()" @click="resetQueryLocations">
                  <i class="fa fa-fw fa-undo"></i> %s
                </buttonl>
            </div>',
            $this->getId(),
            Yii::t('hipanel:stock', 'Choose stock columns'),
            $activeInput,
            Yii::t('hipanel:stock', 'Back to prefered stock locations'),
        );
    }

    private function registerVueContainer(): void
    {
        $this->view->registerJs(
            /** @lang JavaScript */
            '
          ;(() => {
            $("a.export-report-link").not("[data-export-url]").click(function (evt) {
              evt.preventDefault();
              $.getJSON("get-locations").done(function (locations) {
                const url = new URL(window.location.href);
                const urlParams = new URLSearchParams(window.location.search);
                locations.forEach((location, index) => {
                  urlParams.set(`ModelSearch[locations][${index}]`, location);
                });
                url.search = urlParams.toString();
                $(this).exporter("copy", url.href);
              });
            });
          })();
        '
        );

        $this->view->registerJs(
            sprintf(
                /** @lang JavaScript */
                "
                ;(() => {
                    const container = $('#%s');
                    new Vue({
                        el: container.get(0),
                        components: {
                          'treeselect': VueTreeselect.Treeselect,
                        },
                        data: {
                            value: container.find('input[type=hidden]').data('value'),
                            options: container.find('input[type=hidden]').data('options'),
                            saveOnChange: container.find('input[type=hidden]').data('save-on-change'),
                            allowUpdate: false
                        },
                        methods: {
                          locationsInQueryParams: function () {
                            const urlParams = new URLSearchParams(window.location.search);
                            for (const param of urlParams.keys()) {
                                if (param.startsWith('ModelSearch[locations]')) {
                                    return true;
                                }
                            }
                            return false;
                          },
                          resetQueryLocations: function () {
                            window.location.href = '%s';
                          },
                          onChange: function(value) {
                            if (!this.saveOnChange) {
                              return;
                            }

                            $.post('set-locations', {locations: value}).done(() => {
                              this.allowUpdate = true;
                            }).fail(function(err) {
                              console.error(err.responseText);
                              hipanel.notify.error('Failed to save locations!');
                            });
                          },
                          updateColumns: function () {
                            if (!this.saveOnChange) {
                              return;
                            }

                            const allowUpdate = this.allowUpdate;
                            this.\$nextTick(function () {
                              if (allowUpdate) {
                                if (this.value.length) {
                                  this.allowUpdate = false;
                                  $.pjax.reload({container: '#actualize-locations', async: true});
                                } else {
                                  location.reload();
                                }
                              }
                            });
                          }
                        }
                    });
                })();",
                $this->getId(),
                Url::current(['ModelSearch' => ['locations' => null]]),
            )
        );
    }

    private function buildOptions(): array
    {
        $stockLocationsList = $this->provider->getLocationsList();
        $options = array_merge(
            $this->buildDataCentersTree($stockLocationsList),
            $this->buildCHWTree($stockLocationsList),
            $this->buildRacksTree($stockLocationsList),
        );

        return $options;
    }

    private function buildRacksTree(array $stockLocationsList): array
    {
        $filterByLocationType = function (array $list, string $type) {
            return array_filter($list, function (array $item) use ($type) {
                return str_starts_with($item['category'], 'location') && $item['location_type'] === $type;
            });
        };

        $dcs = $filterByLocationType($stockLocationsList, 'dc');
        $buildings = $filterByLocationType($stockLocationsList, 'building');
        $cages = $filterByLocationType($stockLocationsList, 'cage');
        $racks = $filterByLocationType($stockLocationsList, 'rack');

        $result = $this->nestRackTreeChildren([$dcs, $buildings, $cages, $racks]);

        return [
            [
                'id' => 'location:ANY',
                'label' => Yii::t('hipanel:stock', 'DC, Building, Cage, Rack'),
                'children' => $this->removeKeysRecursively(array_values($result)),
            ],
        ];
    }

    private function nestRackTreeChildren($dataOrders, string $parent_location = null): array|null
    {
        $children = [];
        if ($dataOrders === []) {
            return null;
        }

        $next = array_shift($dataOrders);
        foreach ($next as $item) {
            if ($parent_location === null || str_starts_with($item['location_name'], $parent_location)) {
                $children[$item['location_name']] = [
                    'id' => $item['id'],
                    'label' => $this->provider->getLabel($item),
                ];

                $nested = $this->nestRackTreeChildren($dataOrders, $item['location_name']);
                if ($nested !== null) {
                    $children[$item['location_name']]['children'] = $nested;
                }
            }
        }

        return $children;
    }

    private function buildDataCentersTree(mixed $stockLocationsList): array
    {
        $children = [];
        $groups = array_filter(
            $stockLocationsList,
            static fn($l) => $l['category'] === 'stock_group' && $l['id'] !== 'stock:ANY'
        );
        $stocks = array_filter(
            $stockLocationsList,
            static fn($l) => $l['category'] === 'stock' && $l['id'] !== 'stock:ANY'
        );
        foreach ($groups as $g) {
            $children[$g['location_name']]['id'] = $g['id'];
            $children[$g['location_name']]['label'] = $this->provider->getLabel($g);
        }
        foreach ($stocks as $s) {
            if (isset($children[$s['location_name']])) {
                $children[$s['location_name']]['children'][$s['id']] = [
                    'id' => $s['id'],
                    'label' => $this->provider->getLabel($s),
                ];
            } else {
                $children[$s['location_name']] = [
                    'id' => $s['id'],
                    'label' => $this->provider->getLabel($s),
                ];
            }
        }
        if ($children === []) {
            return [];
        }

        return [
            [
                'id' => 'stock:ANY',
                'label' => 'Any stocks',
                'children' => $this->removeKeysRecursively(array_values($children)),
            ],
        ];
    }

    private function buildCHWTree(mixed $stockLocationsList): array
    {
        $children = [];
        $locations = array_filter(
            $stockLocationsList,
            static fn($l) => in_array($l['category'], ['chwbox', 'chwbox_group']) && $l['id'] !== 'chwbox'
        );
        foreach ($locations as $l) {
            $l['customer'] = $this->provider->getCustomer($l);
            if ($l['category'] === 'chwbox_group' && $l['location_type'] === 'chwbox_group' && $l['location_name'] === $l['customer']) {
                $children[$l['customer']]['id'] = $l['id'];
                $children[$l['customer']]['label'] = $this->provider->getLabel($l);
            } else if ($l['category'] === 'chwbox_group' && $l['location_name'] !== $l['customer']) {
                $children[$l['customer']]['children'][$l['location_name']]['id'] = $l['id'];
                $children[$l['customer']]['children'][$l['location_name']]['label'] = $this->provider->getLabel($l);
            } else if ($l['category'] === 'chwbox') {
                $children[$l['customer']]['children'][$l['location_name']]['children'][$l['id']]['id'] = $l['id'];
                $children[$l['customer']]['children'][$l['location_name']]['children'][$l['id']]['label'] = $this->provider->getLabel($l);
            }
        }
        if ($children === []) {
            return [];
        }

        return [
            [
                'id' => 'chwbox',
                'label' => 'Any Customer HW Boxes',
                'children' => $this->removeKeysRecursively(array_values($children)),
            ],
        ];
    }
}
