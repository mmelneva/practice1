<?php namespace App\Models\Features;

/**
 * Class AttachedToNode
 * Functional to attached model to Node.
 * @package Feature
 */
trait AttachedToNode
{
    /**
     * Node association.
     * @return mixed
     */
    public function node()
    {
        return $this->belongsTo('App\Models\Node');
    }
}
