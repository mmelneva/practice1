<?php
namespace Diol\Fileclip\Version;

use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;

/**
 * Interface IVersion
 * Interface to modify image
 * @package Diol\Fileclip\Version
 */
interface IVersion
{
    /**
     * Modify image
     * @param ImageInterface $image
     * @param ImagineInterface $imagine
     * @return mixed
     */
    public function modify(ImageInterface $image, ImagineInterface $imagine);

    /**
     * Get options for image
     *
     * @return []
     */
    public function getOptions();
}
