<?php declare(strict_types=1);

namespace hipanel\modules\stock\widgets;

use hipanel\components\SettingsStorage;
use hipanel\helpers\StringHelper;
use hipanel\modules\stock\helpers\StockLocationsProvider;
use hipanel\modules\stock\models\VO\LocationItem;
use hipanel\widgets\HookTrait;
use hipanel\widgets\VueTreeSelectInput;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
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
    )
    {
        parent::__construct($config);
    }

    /**
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        $this->useStorage = !$this->hasModel();
        $this->value = $this->hasModel()
            ? (empty($this->model->{$this->attribute}) ? [] : StringHelper::explode($this->model->{$this->attribute}))
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

        $this->view->registerCss(
            sprintf(
                '
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
        ',
                $this->getId()
            )
        );

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
                  :value-consists-of="selectionMode"
                  delimiter=","
                  sort-value-by="LEVEL"
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
                    <div slot="after-list" style="padding: 0 0.5em 0;">
                      <hr style="margin: 0.5em 0;">
                      <label style="margin-bottom: 0.5em; display: block; cursor: pointer;">
                        <input type="checkbox" v-model="includeDescendants" @change="onModeChange"> %s
                      </label>
                    </div>
                </treeselect>
                %s
                <button class="btn btn-xs btn-danger btn-flat" v-cloak v-show="locationsInQueryParams()" @click="resetQueryLocations">
                  <i class="fa fa-fw fa-undo"></i> %s
                </buttonl>
            </div>',
            $this->getId(),
            Yii::t('hipanel:stock', 'Choose stock columns'),
            Yii::t('hipanel:stock', 'Include all descendants'),
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
                    const STORAGE_KEY = 'stockLocations.includeDescendants';
                    
                    new Vue({
                        el: container.get(0),
                        components: {
                          'treeselect': VueTreeselect.Treeselect,
                        },
                        data: {
                            value: container.find('input[type=hidden]').data('value'),
                            options: container.find('input[type=hidden]').data('options'),
                            saveOnChange: container.find('input[type=hidden]').data('save-on-change'),
                            allowUpdate: false,
                            includeDescendants: localStorage.getItem(STORAGE_KEY) === 'true'
                        },
                        computed: {
                          selectionMode: function() {
                            return this.includeDescendants ? 'ALL' : 'BRANCH_PRIORITY';
                          }
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
                          onModeChange: function() {
                            localStorage.setItem(STORAGE_KEY, this.includeDescendants);
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
        $stockLocationsList = $this->provider->getAllLocations();

        $aliasGroupTree = $this->buildAliasGroupTree($stockLocationsList);
//        $anyStockTree = $this->buildDataCentersTree($stockLocationsList); // todo: legacy tree, may not be useful in the future
        $chwTree = $this->buildCHWTree($stockLocationsList);
        $rackTree = $this->buildRacksTree($stockLocationsList);

        return array_merge(
            $aliasGroupTree,
            $chwTree,
            $rackTree,
        );
    }

    private function buildAliasGroupTree(array $stockLocationsList): array
    {
        $result = [];
        $locations = array_filter($stockLocationsList, static fn(LocationItem $l) => $l->category->value === 'alias_group_by_stock_state');
        $stocks = ArrayHelper::index(array_filter(
            $stockLocationsList,
            static fn(LocationItem $l) => $l->category->value === 'stock' && $l->id !== 'stock:ANY'
        ), 'id');

        foreach ($locations as $l) {
            if (str_ends_with($l->id, ':ANY')) {
                $result[$l->type->value]['id'] = $l->id;
                $result[$l->type->value]['label'] = $l->label;
            } else {
                $item = ['id' => $l->id, 'label' => $l->label];
                foreach ($l->objects as $objName) {
                    $item['children'][$objName] = ['id' => $objName, 'label' => $objName];
                    if (isset($stocks[$objName])) {
                        $item['children'][$objName]['label'] = $stocks[$objName]->label;
                    }
                }
                $result[$l->type->value]['children'][$l->id] = $item;
            }
        }
        $sortedResult = array_merge(array_flip([
            'alias_group_stock',
            'alias_group_used',
            'alias_group_rma',
            'alias_group_for-test',
        ]), $result);

        return [
            [
                'id' => 'alias_group',
                'label' => Yii::t('hipanel:stock', 'Alias groups'),
                'children' => $this->removeKeysRecursively(array_values($sortedResult)),
            ],
        ];
    }

    /**
     * @param $stockLocationsList LocationItem[]
     */
    private function buildRacksTree(array $stockLocationsList): array
    {
        $filterByLocationType = fn(array $list, string $type) => array_filter(
            $list,
            fn(LocationItem $item) => str_starts_with($item->category->value, 'location') && $item->type->value === $type
        );

        $dcs = $filterByLocationType($stockLocationsList, 'dc');
        $buildings = $filterByLocationType($stockLocationsList, 'building');
        $cages = $filterByLocationType($stockLocationsList, 'cage');
        $racks = $filterByLocationType($stockLocationsList, 'rack');

        $result = $this->nestTreeChildren([$dcs, $buildings, $cages, $racks]);

        return [
            [
                'id' => 'location:ANY',
                'label' => Yii::t('hipanel:stock', 'DC, Building, Cage, Rack'),
                'children' => $this->removeKeysRecursively(array_values($result)),
            ],
        ];
    }

    private function nestTreeChildren($dataOrders, string $parent_location = null): array|null
    {
        $children = [];
        if ($dataOrders === []) {
            return null;
        }

        $next = array_shift($dataOrders);
        foreach ($next as $item) {
            if ($parent_location === null || str_starts_with($item->name, $parent_location)) {
                $children[$item->name] = [
                    'id' => $item->id,
                    'label' => $item->label,
                ];

                $nested = $this->nestTreeChildren($dataOrders, $item->name);
                if ($nested !== null) {
                    $children[$item->name]['children'] = $nested;
                }
            }
        }

        return $children;
    }

    /**
     * @param $stockLocationsList LocationItem[]
     */
    private function buildDataCentersTree(array $stockLocationsList): array
    {
        $children = [];
        $groups = array_filter(
            $stockLocationsList,
            static fn(LocationItem $l) => $l->category->value === 'stock_group' && $l->id !== 'stock:ANY'
        );
        $stocks = array_filter(
            $stockLocationsList,
            static fn(LocationItem $l) => $l->category->value === 'stock' && $l->id !== 'stock:ANY'
        );
        foreach ($groups as $g) {
            $children[$g->name]['id'] = $g->id;
            $children[$g->name]['label'] = $g->label;
        }
        foreach ($stocks as $s) {
            if (isset($children[$s->name])) {
                $children[$s->name]['children'][$s->id] = [
                    'id' => $s->id,
                    'label' => $s->label,
                ];
            } else {
                $children[$s->name] = [
                    'id' => $s->id,
                    'label' => $s->label,
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

    /**
     * @param $stockLocationsList LocationItem[]
     */
    private function buildCHWTree(array $stockLocationsList): array
    {
        $children = [];
        $locations = array_filter(
            $stockLocationsList,
            static fn(LocationItem $l) => in_array($l->category->value, ['chwbox', 'chwbox_group']) && $l->id !== 'chwbox'
        );
        foreach ($locations as $l) {
            if ($l->category->value === 'chwbox_group' && $l->type->value === 'chwbox_group' && $l->name === $l->customer) {
                $children[$l->customer]['id'] = $l->id;
                $children[$l->customer]['label'] = $l->label;
            } else if ($l->category->value === 'chwbox_group' && $l->name !== $l->customer) {
                $children[$l->customer]['children'][$l->name]['id'] = $l->id;
                $children[$l->customer]['children'][$l->name]['label'] = $l->label;
            } else if ($l->category->value === 'chwbox') {
                $children[$l->customer]['children'][$l->name]['children'][$l->id]['id'] = $l->id;
                $children[$l->customer]['children'][$l->name]['children'][$l->id]['label'] = $l->label;
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
