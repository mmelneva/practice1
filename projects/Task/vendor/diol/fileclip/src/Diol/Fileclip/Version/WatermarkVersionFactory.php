<?php
namespace Diol\Fileclip\Version;

use Config;

/**
 * Class WatermarkVersionFactory
 * @package App\Service\FileClip\Version
 */
class WatermarkVersionFactory
{
    /**
     * @param IVersion $version
     * @param array $options
     * @param string $path path to watermark file
     * @return self
     */
    public static function newInstance(IVersion $version = null, $options = [], $path = null)
    {
        $watermarkFile = !is_null($path) ? $path : Config::get('fileclip::watermark_image');
        if (is_null($version)) {
            $version = new DefaultVersion();
        }
        return is_file($watermarkFile) ? new WatermarkVersion($watermarkFile, $version, $options) : $version;
    }
}
