<?php namespace App\Services\Validation\Banner;

use App\Services\Validation\AbstractLaravelValidator;

/**
 * Class BannerLaravelValidator
 * @package  App\Services\Validation\Banner
 */
class BannerLaravelValidator extends AbstractLaravelValidator
{
    protected function getRules()
    {
        return [
            'position' => 'integer',
            'image_file' => 'local_or_remote_image:jpeg,jpg,png,gif',
        ];
    }
}
