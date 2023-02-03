<?php namespace App\Services\Providers;

use Illuminate\Support\ServiceProvider;

class ErrorsServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->pushError(
            function (\Exception $exception, $code) {
                if ($code == 404) {
                    if (\Request::is('cc/*')) {
                        return \Response::view('admin.errors.404', [], 404);
                    } else {
                        return $this->app->make('App\Controllers\Client\ErrorsController')->getError404();
                    }
                } else {
                    return null;
                }
            }
        );

        $this->app->pushError(
            function (\Exception $exception, $code) {
                if ($code == 403) {
                    return \Response::view('admin.errors.403', [], 403);
                } else {
                    return null;
                }
            }
        );

        $this->app->pushError(
            function (\Exception $exception, $code) {
                if (\Config::get('app.debug') !== true) {
                    $this->app['error_sender']->send($exception, $code);
                    return \Response::view('admin.errors.500', [], 500);
                } else {
                    return null;
                }
            }
        );
    }
}
