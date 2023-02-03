<?php namespace Diol\LaravelErrorSender;

use Exception;
use Illuminate\Cache\Repository as CacheRepository;

/**
 * Class ExceptionStorage
 * @package Diol\LaravelErrorSender
 */
class ExceptionStorage
{
    private $cache;
    private $lifeTimeInSeconds;

    public function __construct(CacheRepository $cache, $lifeTimeInSeconds)
    {
        $this->cache = $cache;
        $this->lifeTimeInSeconds = $lifeTimeInSeconds;
    }

    public function put(Exception $exception)
    {
        $this->cache->put($this->uid($exception), 1, $this->lifeTimeInSeconds / 60);
    }

    public function has(Exception $exception)
    {
        return $this->cache->has($this->uid($exception));
    }

    public function forget(Exception $exception)
    {
        $this->cache->forget($this->uid($exception));
    }

    public function available()
    {
        try {
            $this->cache->put('test-key', 'test-value', 0);

            return 'test-value' === $this->cache->pull('test-key');

        } catch (Exception $exception) {
            return false;
        }
    }

    private function uid(Exception $exception)
    {
        return md5(json_encode(
            [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'line' => $exception->getFile(),
                'file' => $exception->getLine()
            ]
        ));
    }
}
