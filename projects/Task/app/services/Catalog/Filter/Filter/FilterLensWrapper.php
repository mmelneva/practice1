<?php namespace App\Services\Catalog\Filter\Filter;

use App\Services\Catalog\Filter\Lens\LensInterface;

/**
 * Class FilterLensWrapper
 * Wrapper for lens.
 *
 * @package App\Services\Catalog\Filter\Filter
 */
class FilterLensWrapper
{
    private $lens;
    private $key;
    private $name;
    private $image;
    private $view;
    private $optional;

    public function __construct(LensInterface $lens, $key, $name, $image, $view, $optional = [])
    {
        $this->lens = $lens;
        $this->key = $key;
        $this->name = $name;
        $this->image = $image;
        $this->view = $view;
        $this->optional = $optional;
    }

    /**
     * @return LensInterface
     */
    public function getLens()
    {
        return $this->lens;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return string
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @return array
     */
    public function getOptional()
    {
        return $this->optional;
    }
}
