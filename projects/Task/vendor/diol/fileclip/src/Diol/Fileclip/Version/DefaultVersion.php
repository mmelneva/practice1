<?php namespace Diol\Fileclip\Version;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;

/**
 * Class DefaultVersion
 * @package Diol\Fileclip\Version
 */
class DefaultVersion implements IVersion
{
    const MAX_WIDTH = 1500;
    const MAX_HEIGHT = 1500;

    public function modify(ImageInterface $image, ImagineInterface $imagine)
    {
        return $image->thumbnail(new Box(self::MAX_WIDTH, self::MAX_HEIGHT));
    }

    public function getOptions()
    {
        return [];
    }
}
