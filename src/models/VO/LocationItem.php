<?php declare(strict_types=1);

namespace hipanel\modules\stock\models\VO;

use hipanel\helpers\StringHelper;
use hipanel\modules\stock\enums\StockLocationCategory;
use hipanel\modules\stock\enums\StockLocationType;
use yii\helpers\Json;

final readonly class LocationItem
{
    public string $customer;
    public string $label;
    public string $icon;

    public function __construct(
        public string $id,
        public StockLocationCategory $category,
        public StockLocationType $type,
        public string $name,
        public string $customers,
        public array $objects,
    )
    {
        $this->customer = $this->getCustomer();
        $this->label = $this->getLabel();
        $this->icon = $this->getIcon();
    }

    public static function fromArray(array $data): self
    {
        $objects = Json::decode($data['objects'] ?? '[]');

        return new self(
            id: (string)$data['id'],
            category: StockLocationCategory::from($data['category']),
            type: StockLocationType::from($data['location_type']),
            name: (string)$data['location_name'],
            customers: (string)($data['customers'] ?? ''),
            objects: $objects,
        );
    }

    private function getLabel(): string
    {
        if ($this->id === 'chwbox' || $this->id === 'stock:ANY' || str_starts_with($this->id, 'alias_group')) {
            return $this->name;
        }

        return match ($this->category) {
            StockLocationCategory::STOCK => implode(':', [$this->type->value, $this->name]),
            StockLocationCategory::STOCK_GROUP => $this->name,
            StockLocationCategory::CHWBOX_GROUP => $this->name === $this->customer
                ? $this->name
                : implode('/', [StringHelper::truncate($this->customer, 7), $this->name]),
            StockLocationCategory::CHWBOX => $this->id,
            default => ($this->type === StockLocationType::CHWBOX_GROUP) ? $this->customer : $this->id,
        };
    }

    private function getCustomer(): string
    {
        $customers = explode(',', str_replace(['{', '}'], '', $this->customers));

        return reset($customers);
    }

    private function getIcon(): string
    {
        return match ($this->type) {
            StockLocationType::CHWBOX => 'fa-user',
            StockLocationType::CHWBOX_GROUP => 'fa-users',
            StockLocationType::DELETED => 'fa-ban',
            StockLocationType::RMA => 'fa-wrench',
            StockLocationType::SOLD => 'fa-usd',
            StockLocationType::STOCK => 'fa-cube',
            StockLocationType::SUPPLIER => 'fa-shopping-cart',
            StockLocationType::TRASH => 'fa-trash-o',
            StockLocationType::USED => 'fa-recycle',
            StockLocationType::RACK => 'fa-server',
            StockLocationType::CAGE => 'fa-navicon fa-rotate-90',
            StockLocationType::BUILDING => 'fa-university',
            StockLocationType::DC => 'fa-building-o',
            default => 'fa-cubes',
        };
    }
}
