<?php declare(strict_types=1);

namespace hipanel\modules\stock\helpers;

use hipanel\components\SettingsStorage;
use hipanel\modules\stock\enums\StockLocationCategory;
use hipanel\modules\stock\enums\StockLocationType;
use hipanel\modules\stock\models\VO\LocationItem;
use hipanel\modules\stock\repositories\StockLocationsRepository;
use yii\web\Request;

/**
 * Class StockLocationsProvider
 *
 * @psalm-type StockLocationCategory = "stock"|"stock_group"|"chwbox"|"chwbox_group"|"location"|"location_group"|"alias_group"
 * @psalm-type StockLocationType = "alias"|"building"|"cage"|"chwbox"|"chwbox_group"|"dc"|"for-test"|"location"|"rack"|"rma"|"stock"|"stock_group"|"used"
 * @psalm-type LocationItem = array{
 *     category: StockLocationCategory,
 *     id: string,
 *     location_type: StockLocationType,
 *     location_name: string,
 *     customers: string,
 *     objects: array<string, string>
 * }
 * @psalm-type StockLocationList = list<LocationItem>
 */
class StockLocationsProvider
{
    /** @var StockLocationList|null */
    private ?array $locations = null;
    private const string KEY = 'stock-locations-list';

    public function __construct(
        private readonly SettingsStorage $storage,
        private readonly Request $request,
        private readonly StockLocationsRepository $locationsRepository,
    )
    {
    }

    /**
     * @return LocationItem[]
     */
    public function getAllLocations(): array
    {
        return $this->locationsRepository->getLocations();
    }

    public function setLocations(array $locations): void
    {
        $this->storage->setBounded(self::KEY, $locations);
    }

    public function getLocations(): array
    {
        $locations = $this->request->get('ModelSearch') ? $this->request->get('ModelSearch')['locations'] ?? null : null;

        if ($locations !== null) {
            $this->locations = $locations;
        }
        if ($this->locations === null) {
            $this->locations = $this->storage->getBounded(self::KEY);
        }

        if (!empty($this->locations)) {
            // Filter available locations based on selected IDs
            $allLocations = $this->getAllLocations();

            // Extract IDs from the VO list
            $availableIds = array_map(fn(LocationItem $item) => $item->id, $allLocations);

            // Intersect saved user preferences with actually available locations
            $validSelectedIds = array_intersect($this->locations, $availableIds);

            return array_values($validSelectedIds);
        }

        return [];
    }

    private function getStockTypesWithAliasGroups(): array
    {
        $result = [];
        $stockTypes = ['stock', 'used', 'rma', 'for-test'];
        $locations = $this->getLocations();

        foreach ($stockTypes as $type) {
            foreach ($locations as $alias) {
                if ($alias->type->value !== 'alias_group') {
                    continue;
                }

                foreach ($alias->objects as $id => $objectName) {
                    foreach ($locations as $location) {
                        if ($location->type->value === $type && $location->id === $objectName) {
                            $result[$type][$alias->name][$location->name] = $location->id;
                            //      -stock -AMS17        -AMS17_Z6G         -stock_AMS17_Z6G
                        }
                    }
                }
            }
        }

        return $result;
        // category: alias_group_by_stock_state
        // alias_group_stock:AMS17
        // alias_group_rma:AMS17
        // alias_group_for-test:AMS17
        // alias_group_used:AMS17
    }
}
