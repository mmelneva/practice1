<?php namespace App\Services\FormProcessors\Features;

/**
 * Class AutoAlias
 * @package App\Services\FormProcessors\Features
 */
trait AutoAlias
{
    /**
     * Set auto alias, based on name.
     *
     * @param array $data
     * @return array
     */
    public function setAutoAlias(array $data)
    {
        $alias = !empty($data['alias']) ? $data['alias'] : array_get($data, 'name');
        $data['alias'] = \Str::alias($alias);

        return $data;
    }
}
