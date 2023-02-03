<?php namespace App\Models\Features;

/**
 * Trait AutoPublish
 *
 * Trait which addes feature to get published models by default.
 *
 * @package App\Models\Features
 */
trait AutoPublish
{
    public function getPublishAttribute()
    {
        return array_get($this->attributes, 'publish', true);
    }
}
