<?php
namespace Diol\Fileclip\Version;

use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Point;
use Imagine\Image\Box;
use Illuminate\Support\Facades\App;


class WatermarkVersion implements IVersion
{
    private $path;
    private $version;

    /**
     * Create version handler for watermark version
     * @param $path
     * @param IVersion $version
     * @param array $options
     */
    public function __construct($path, IVersion $version = null, array $options = [])
    {
        $this->path = $path;
        $this->options = $options;
        $this->version = $version;
    }

    /**
     * {@inheritDoc}
     */
    public function modify(ImageInterface $image, ImagineInterface $imagine)
    {
        $watermark = $imagine->open($this->path);

        if ($this->version instanceof IVersion) {
            $image = $this->version->modify($image, $imagine);
        }

        $size = $image->getSize();
        $wSize = $watermark->getSize();

        if ($size->getWidth() < $wSize->getWidth() || $size->getHeight() < $wSize->getHeight()) {
            $watermark = $watermark->thumbnail(new Box($size->getWidth(), $size->getHeight()));
        }

        $wSize = $watermark->getSize();
        $centerPosition = new Point(
            (integer)floor(($size->getWidth() - $wSize->getWidth()) / 2),
            (integer)floor(($size->getHeight() - $wSize->getHeight()) / 2)
        );
        $image = $image->paste($watermark, $centerPosition);
        return $image;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }
}