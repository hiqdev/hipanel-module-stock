<?php declare(strict_types=1);

namespace hipanel\modules\stock\helpers;

use hipanel\components\SettingsStorage;
use hipanel\modules\stock\models\VO\LocationItem;
use hipanel\modules\stock\repositories\StockLocationsRepository;
use yii\web\Request;

class StockLocationsProvider
{
    /** @var LocationItem[]|null */
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
}
