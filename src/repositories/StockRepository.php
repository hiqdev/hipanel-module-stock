<?php

declare(strict_types=1);


namespace hipanel\modules\stock\repositories;

use hipanel\modules\stock\models\ModelGroup;
use yii\caching\CacheInterface;
use yii\web\Request;
use yii\web\User;

readonly class StockRepository
{
    private const int ONE_HOUR = 60 * 60;

    public function __construct(
        private ModelGroup $modelGroup,
        private CacheInterface $cache,
        private Request $request,
        private User $user,
    )
    {
    }

    public function getStoredAliases(): array
    {
        static $result = [];

        if (!empty($result)) {
            return $result;
        }

        $result = match (true) {
            $this->getFromUrl() !== [] => $this->getFromUrl(),
            $this->getFromApi() !== [] => $this->getFromApi(),
            default => [],
        };

        return $result;
    }

    public function getAllAliases(): array
    {
        return $this->getFromApi();
    }

    private function getFromApi(): array
    {
        $key = ['stock.model_group.aliases', $this->user->id];

        return $this->cache->getOrSet($key, fn(): array => array_keys($this->modelGroup::perform('alias-search')), self::ONE_HOUR);
    }

    private function getFromUrl(): array
    {
        return $this->request->get('ModelGroupSearch')['alias_in'] ?? [];
    }
}
