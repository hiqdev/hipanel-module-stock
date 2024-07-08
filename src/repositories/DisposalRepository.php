<?php declare(strict_types=1);

namespace hipanel\modules\stock\repositories;

use hipanel\helpers\ArrayHelper;
use hipanel\modules\server\models\Server;
use yii\base\Application;
use yii\caching\CacheInterface;

readonly class DisposalRepository
{
    public function __construct(public CacheInterface $cache, public Application $app)
    {
    }

    public function findForLocation(?string $deviceLocation): array
    {
        $devices = $this->getDevices();
        $deviceId2Location = array_filter(ArrayHelper::map($devices, 'id', 'bindings.location.switch'));
        if ($deviceLocation === null) {
            return $devices;
        }

        return $this->sortBySimilarity($deviceId2Location, $deviceLocation);
    }

    private function getDevices(): array
    {
        return $this->cache->getOrSet(
            ['disposal_id', $this->app->user->identity->id],
            fn() => Server::find()->where(['dc_like' => 'disposal_'])->withBindings()->limit(-1)->all(),
            100 //3600
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
