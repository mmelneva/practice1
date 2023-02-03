<?php namespace App\Services\RepositoryFeatures\Caching;

interface CachingExecutorInterface
{
    /**
     * Run function or get result value from cache.
     *
     * @param $key
     * @param callable $callable
     * @param boolean $cached
     * @return mixed
     */
    public function execute($key, callable $callable, $cached = true);
}
