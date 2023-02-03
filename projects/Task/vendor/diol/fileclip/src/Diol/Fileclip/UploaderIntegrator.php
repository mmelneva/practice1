<?php
namespace Diol\Fileclip;

use Diol\Fileclip\Uploader\Uploader;
use Diol\Fileclip\Version\WatermarkVersionFactory;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

/**
 * Class UploaderIntegrator
 * Uploader integrator, which allows convenient way to mount uploaders to models
 * @package Diol\Fileclip
 */
class UploaderIntegrator
{
    /**
     * Get uploader, with storage root stored in constant 'fileclip::storage_root',
     * name generator object 'fileclip::name_generator',
     * and imagine object 'fileclip::imagine'
     *
     * @param string $path - relative path to exact storage
     * @param array $versions - list of versions
     * @return Uploader
     */
    public static function getUploader($path, array $versions = [])
    {
        return new Uploader(
            Config::get('fileclip::public_root'),
            Config::get('fileclip::storage_root'),
            App::make('fileclip::name_generator'),
            $path,
            App::make('fileclip::imagine'),
            $versions
        );
    }

    /**
     * Get default uploader like getUploader, but all the versions will be watermarked.
     *
     * @param $path
     * @param array $versions
     * @return Uploader
     */
    public static function getWatermarkedUploader($path, array $versions = [])
    {
        if (!isset($versions['default'])) {
            $versions['default'] = null;
        }

        $preparedVersions = [];
        foreach ($versions as $versionName => $versionHandler) {
            $preparedVersions[$versionName] = WatermarkVersionFactory::newInstance($versionHandler);
        }

        return self::getUploader($path, $preparedVersions);
    }
}
