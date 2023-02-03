# Laravel Sweet Tooltip

## Descriprion

Laravel package to integrate Sweet Tooltip version 1.3.1.

## Requirements

JQuery.

## Installation

```bash
composer require diol/laravel-sweet-tooltip 1.*
```

## Configuration

Require service provider (app/config/app.php):
```php
<?php
return [
    // ...
    'providers' => [
        // ...
        'Diol\LaravelSweetTooltip\ServiceProvider',
        // ...
    ],
    // ...
];
```

Publish config:
```bash
php artisan config:publish diol/laravel-sweet-tooltip
```

Edit configuration file: **app/config/packahes/diol/laravel-sweet-tooltip/config.php**


## Manual configuration

If you do not want tooltip to be automatically added to page, you could do it manually.

Turn off automatic injection:
```php
<?php

return [
    'auto_inject' => false,
    'rules' => [
        // ...
    ],
];

```

Add top view in place what you want:
```blade
@include('laravel-sweet-tooltip::default')
```
