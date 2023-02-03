<?php
namespace Diol\Fileclip\Version;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;

/**
 * Class BoxVersion
 * Version handler which limit max image size
 * @package Diol\Fileclip\Version
 */
class BoxVersion implements IVersion
{
    /**
     * Max width
     * @var int
     */
    private $width;

    /**
     * Max height
     * @var int
     */
    private $height;

    /**
     * Box options
     *
     * @var array
     */
    private $options;

    /**
     * Create version handler for box version
     * @param $width
     * @param $height
     * @param $options
     */
    public function __construct($width, $height, array $options = [])
    {
        $this->width = $width;
        $this->height = $height;
        $this->options = $options;
    }

    /**
     * {@inheritDoc}
     */
    public function modify(ImageInterface $image, ImagineInterface $imagine)
    {
        return $image->thumbnail(new Box($this->width, $this->height));
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }
}
