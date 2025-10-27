<?php

declare(strict_types=1);


namespace hipanel\modules\stock\repositories;

use hipanel\helpers\ArrayHelper;
use hipanel\modules\server\models\Server;
use yii\base\Application;
use yii\caching\CacheInterface;

readonly class LocationRepository
{
    public function __construct(public CacheInterface $cache, public Application $app)
    {
    }

    public function findForLocation(?string $deviceLocation, string $locationLike = 'disposal_'): array
    {
        $devices = $this->getDevices($locationLike);
        $deviceId2Locations = array_filter(ArrayHelper::map($devices, 'id', 'bindings.location.switch'));
        $deviceLocationIsEmptyOrDevicesAreNotBoundWithLocation = $deviceLocation === null || $deviceId2Locations === [];
        if ($deviceLocationIsEmptyOrDevicesAreNotBoundWithLocation) {
            return ArrayHelper::map($devices, 'id', 'name');
        }

        return $this->sortBySimilarity($deviceId2Locations, $deviceLocation);
    }

    private function getDevices(string $dcLike): array
    {
        return $this->cache->getOrSet(
            ['disposal_id', $dcLike, $this->app->user->identity->id],
            static fn() => Server::find()->where(['dc_like' => $dcLike])->withBindings()->limit(-1)->all(),
            3600
        );
    }

    function sortBySimilarity(array $strings, string $sample): array
    {
        $similarityScores = [];

        foreach ($strings as $index => $string) {
            similar_text($sample, $string, $percent);
            $similarityScores[$index] = $percent;
        }

        arsort($similarityScores);

        $sortedStrings = [];
        foreach (array_keys($similarityScores) as $index) {
            $sortedStrings[$index] = $strings[$index];
        }

        return $sortedStrings;
    }
}
