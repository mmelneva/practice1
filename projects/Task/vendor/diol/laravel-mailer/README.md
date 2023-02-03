# Laravel-mailer

Extended mailer for Laravel 4.2

## Installation

Require this package in your composer.json and run composer update:

```json
{
	"require": {
        "diol/laravel-mailer": "1.*"
	}
}
```

After updating composer, add the ServiceProvider to the providers array in `app/config/app.php`

```php
'providers' => [
	Diol\LaravelMailer\ServiceProvider',
],
```

## Basic usage

Use extended mailer as original mailer.

Example:

```php
    \Mail::send($view, $data, $callback);
```
 
