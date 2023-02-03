<?php namespace App\Services\RepositoryFeatures\Caching;

class CachingExecutor implements CachingExecutorInterface
{
    /**
     * @inheritdoc
     */
    public function execute($key, callable $callable, $cached = true)
    {
        static $cache;

        if ($cached) {
            if (!is_array($cache) || !array_key_exists($key, $cache)) {
                $cache[$key] = $callable();
            }
            return $cache[$key];
        } else {
            return $callable();
        }
    }
}
