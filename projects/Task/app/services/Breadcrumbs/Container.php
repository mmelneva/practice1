<?php namespace App\Services\Breadcrumbs;

/**
 * Class Container
 * Breadcrumbs container.
 *
 * @package App\Services\Breadcrumbs
 */
class Container
{
    /**
     * @var array
     */
    private $breadcrumbs = [];

    /**
     * Add breadcrumb.
     *
     * @param string $name
     * @param null|string $url
     */
    public function add($name, $url = null)
    {
        $this->breadcrumbs[] = ['name' => $name, 'url' => $url];
    }

    /**
     * Change breadcrumb.
     *
     * @param $index
     * @param string $name
     * @param null|string $url
     */
    public function change($index, $name, $url = null)
    {
        array_set($this->breadcrumbs, $index, ['name' => $name, 'url' => $url]);
    }

    /**
     * Get breadcrumbs.
     *
     * @return array
     */
    public function getBreadcrumbs()
    {
        return $this->breadcrumbs;
    }

    /**
     * Get length.
     *
     * @return int
     */
    public function length()
    {
        return count($this->breadcrumbs);
    }
}
