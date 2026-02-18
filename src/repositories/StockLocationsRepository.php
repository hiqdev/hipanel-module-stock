<?php declare(strict_types=1);

namespace hipanel\modules\stock\repositories;

use hipanel\modules\stock\models\Model;
use hipanel\modules\stock\models\VO\LocationItem;
use yii\caching\CacheInterface;
use yii\web\User;

class StockLocationsRepository
{
    private const string CACHE_KEY = 'stock-locations';
    private const int CACHE_DURATION = 60*60*24; // 1 day

    public function __construct(
        private readonly CacheInterface $cache,
        private readonly User $user
    )
    {
    }

    /**
     * @return list<LocationItem>
     */
    public function getLocations(): array
    {
        $data = $this->cache->getOrSet(
            [self::CACHE_KEY, $this->user->id],
            static fn() => Model::perform('stock-locations-list', ['show_deleted' => true]),
            self::CACHE_DURATION
        );

        return array_map(fn(array $item) => LocationItem::fromArray($item), $data);
    }
}
