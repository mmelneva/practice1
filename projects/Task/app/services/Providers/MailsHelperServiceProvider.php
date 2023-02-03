<?php namespace App\Services\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class MailsHelperServiceProvider
 * @package  App\Services\Providers
 */
class MailsHelperServiceProvider extends ServiceProvider
{
    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->app->bindShared(
            'reviews_mails_helper',
            function ($app) {
                return $app->make('App\Services\Mails\ReviewsMailsHelper');
            }
        );

        $this->app->bindShared(
            'order_mails_helper',
            function ($app) {
                return $app->make('App\Services\Mails\OrderMailsHelper');
            }
        );
    }
}
