<?php declare(strict_types=1);

namespace hipanel\modules\stock\repositories;

use hipanel\modules\stock\models\Model;
use hipanel\modules\stock\models\VO\LocationItem;
use yii\caching\CacheInterface;
use yii\web\User;

class StockLocationsRepository
{
    private const string CACHE_KEY = 'stock-locations-objects';
    private const int CACHE_DURATION = 60 * 60 * 24; // 1 day

    public function __construct(
        private readonly CacheInterface $cache,
        private readonly User $user
    ) {
    }

    /**
     * @return list<LocationItem>
     */
    public function getLocations(): array
    {
        return $this->cache->getOrSet(
            [self::CACHE_KEY, $this->user->id],
            static function () {
                $locations = Model::perform('stock-locations-list', ['show_deleted' => true]);

                return array_map(fn(array $item) => LocationItem::fromArray($item), $locations);
            },
            self::CACHE_DURATION
        );
    }
}
