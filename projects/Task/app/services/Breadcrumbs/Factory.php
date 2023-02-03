<?php namespace App\Services\Breadcrumbs;

/**
 * Class Factory
 * @package App\Services\Breadcrumbs
 */
class Factory
{
    /**
     * Get breadcrumb container.
     *
     * @return Container
     */
    public function init()
    {
        return new Container();
    }

    /**
     * Get breadcrumb container, but init it with collection and callback.
     * Each element of collection will be passed in callback.
     * Callback should return array: first return element - name, second - url.
     *
     * @param $collection
     * @param callable $urlGenerator
     * @return Container
     */
    public function initFromCollection($collection, callable $urlGenerator)
    {
        $container = $this->init();

        foreach ($collection as $index => $element) {
            $breadcrumb = $urlGenerator($element, $index);
            $container->add(array_get($breadcrumb, 0), array_get($breadcrumb, 1));
        }

        return $container;
    }
}
