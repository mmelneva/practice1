<?php namespace App\Services\Providers;

use App\Services\ReviewRenewal\Adapter\ReviewRenewalMailer;
use App\Services\ReviewRenewal\Adapter\ReviewStorageAdapter;
use App\Services\ReviewRenewal\ReviewRenewal;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ReviewRenewalServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('review_renewal', function (Application $app) {
            return new ReviewRenewal(
                new ReviewStorageAdapter(
                    $app->make('App\Services\Repositories\Reviews\ReviewsRepositoryInterface'),
                    $app->make('App\Services\Repositories\ReviewsDateChange\ReviewsDateChangeRepositoryInterface')
                ),
                new ReviewRenewalMailer(
                    array_get($_ENV, 'MAIL_REVIEW_RENEWAL_REPORT', ['dioltula@gmail.com', 'diol.tech.info@gmail.com', 'diol-test@yandex.ru']),
                    $app->make('App\Services\Repositories\ReviewsDateChange\ReviewsDateChangeRepositoryInterface')
                )
            );
        });
    }
}
