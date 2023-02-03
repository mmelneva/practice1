<?php namespace Diol\FileclipExif;

/**
 * Class ExifDataCallbackContainer
 * Callback container. Need just because it should be defined once, but callback could be changed.
 *
 * @package Diol\FileclipExif
 */
class ExifDataCallbackContainer
{
    /**
     * @var callable|null
     */
    private $callback;

    /**
     * Set callback.
     *
     * @param callable $callback
     */
    public function setCallback(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Call the callback with the model.
     *
     * @param $model
     * @return array
     */
    public function call($model)
    {
        $callback = $this->callback;

        if (!is_null($callback)) {
            return $callback($model);
        } else {
            return [];
        }
    }
}
