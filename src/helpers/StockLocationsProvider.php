<?php
declare(strict_types=1);

namespace hipanel\modules\stock\helpers;

use hipanel\components\SettingsStorage;
use hipanel\helpers\StringHelper;
use hipanel\modules\stock\models\Model;
use yii\caching\CacheInterface;
use yii\helpers\Html;
use yii\web\User;

class StockLocationsProvider
{
    private const KEY = 'stock-locations-list';
    private ?array $locations = null;

    public function __construct(
        private readonly SettingsStorage $storage,
        private readonly CacheInterface $cache,
        private readonly User $user
    )
    {
    }

    public function getLocationsList(): array
    {
        return $this->cache->getOrSet(
            [self::KEY, $this->user->id],
            fn() => Model::perform('stock-locations-list', ['show_deleted' => true]),
            300
        );
    }

    public function setLocations(array $locations): void
    {
        $this->storage->setBounded(self::KEY, $locations);
    }

    public function getLocations(): array
    {
        if ($this->locations === null) {
            $this->locations = $this->storage->getBounded(self::KEY);
        }
        if (!empty($this->locations)) {
            $locationIds = array_column($this->getLocationsList(), 'id');
            $preserveLocations = array_intersect($this->locations, $locationIds);
            $resetArrayOfLocations = array_values($preserveLocations); // to prevent a situation where, when preparing options for JavaScript, the array becomes an object

            return $resetArrayOfLocations;
        }

        return [];
    }

    public function getIcon(string $location_type): string
    {
        $name = match ($location_type) {
            'chwbox' => 'fa-user',
            'chwbox_group' => 'fa-users',
            'deleted' => 'fa-ban',
            'rma' => 'fa-wrench',
            'sold' => 'fa-usd',
            'stock' => 'fa-cube',
            'supplier' => 'fa-shopping-cart',
            'trash' => 'fa-trash-o',
            'used' => 'fa-recycle',
            'rack' => 'fa-server',
            'cage' => 'fa-navicon fa-rotate-90',
            'building' => 'fa-university',
            'dc' => 'fa-building-o',
            default => 'fa-cubes',
        };

        return Html::tag('span', null, ['class' => "fa fa-fw $name"]);
    }

    public function getLabel(array $location): string
    {
        $customer = $this->getCustomer($location);
        $label = null;
        if ($location['id'] === 'chwbox' || $location['id'] === 'stock:ANY') {
            $label = $location['location_name'];
        } else if ($location['category'] === 'stock') {
            $label = implode(':', [$location['location_type'], $location['location_name']]) ;
        } else if ($location['category'] === 'stock_group') {
            $label = $location['location_name'];
        } else if ($location['category'] === 'chwbox_group' && $location['location_name'] === $customer) {
            $label = $location['location_name'];
        } else if ($location['category'] === 'chwbox_group' && $location['location_name'] !== $customer) {
            $label = implode('/', [StringHelper::truncate($customer, '7'), $location['location_name']]);
        } else if ($location['category'] === 'chwbox') {
            $label = $location['id'];
        } else if ($location['category'] === 'chwbox_group' && $location['location_type'] === 'chwbox') {
            $label = implode(' / ', [$customer, $location['location_name']]);
        } else if ($location['location_type'] === 'chwbox_group') {
            $label = $customer;
        }

        return $label ?? $location['id'];
    }

    public function getCustomer(array $location): string
    {
        $customers = explode(',', str_replace(['{', '}'], '', $location['customers'] ?? ''));

        return reset($customers);
    }
}
