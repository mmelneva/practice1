<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/

Artisan::add(new \App\Commands\ConfigFilesCommand());
Artisan::add(new \App\Commands\GetRequestFilter());
Artisan::add(new \App\Commands\FilePermissions());
Artisan::add(new \App\Commands\GetCron());
Artisan::add(new \App\Commands\SitemapGenerate());
Artisan::add(new \App\Commands\RandomTypeOrderButton());
Artisan::add(new \App\Commands\RenewReviews());

Artisan::add(new \App\Commands\RecountProductTypePages());
Artisan::add(new \App\Commands\RebuildProductTypePageSort());
