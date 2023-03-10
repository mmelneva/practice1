<?php

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path() . '/logs/laravel_' . date('Y-m-d') . '.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(
    function (Exception $exception, $code) {
        Log::error($exception);
    }
);

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(
    function () {
        return Response::make("Be right back!", 503);
    }
);

/*
|--------------------------------------------------------------------------
| Require The Helper File
|--------------------------------------------------------------------------
|
| Next we will load the helpers file for the application. This gives us
| a nice separate location to store our helper functions.
|
*/

require app_path() . '/helpers.php';
