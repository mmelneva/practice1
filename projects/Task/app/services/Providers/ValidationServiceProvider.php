<?php namespace App\Services\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class ValidationServiceProvider
 * @package App\SocioCompass\Providers
 */
class ValidationServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register()
    {
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->app->validator->extend(
            'allowed_ip_list',
            'App\Services\Validation\ValidationRules\IpValidationRule@validateAllowedIpList'
        );

        $this->app->validator->extend('subset', 'App\Services\Validation\ValidationRules\Common@validateSubset');
        $this->app->validator->replacer(
            'subset',
            function ($message, $attribute, $rule, $parameters) {
                return str_replace(':variants', implode(', ', $parameters), $message);
            }
        );


        $this->app->validator->extend(
            'multi_key_exists',
            'App\Services\Validation\ValidationRules\Common@validateMultiKeyExists'
        );

        $this->app->validator->extend(
            'multi_exists',
            'App\Services\Validation\ValidationRules\Common@validateMultiExists'
        );


        $this->app->validator->extend(
            'phone',
            'App\Services\Validation\ValidationRules\Common@validatePhone'
        );
        $this->app->validator->replacer(
            'phone',
            function ($message, $attribute, $rule, $parameters) {
                return str_replace(':value', '', $message);
            }
        );


        $this->app->validator->extend(
            'more_than',
            'App\Services\Validation\ValidationRules\Common@validateMoreThan'
        );
        $this->app->validator->replacer(
            'more_than',
            function ($message, $attribute, $rule, $parameters) {
                return str_replace(':value', $parameters[0], $message);
            }
        );

        $this->app->validator->extend(
            'product_publish',
            'App\Services\Validation\ValidationRules\CatalogProductRule@validateProductPublish'
        );

        $this->app->validator->extend(
            'product_price',
            'App\Services\Validation\ValidationRules\CatalogProductRule@validateProductPrice'
        );

        $this->app->validator->extend(
            'local_or_remote_image',
            'App\Services\Validation\ValidationRules\File@validateLocalOrRemoteFile'
        );
        $this->app->validator->replacer(
            'local_or_remote_image',
            function ($message, $attribute, $rule, $parameters) {
                return '';
            }
        );

        $this->app->validator->extend(
            'imported_file',
            'App\Services\Validation\ValidationRules\FileImport@validateImportedFile'
        );
    }
}
