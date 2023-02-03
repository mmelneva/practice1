<?php namespace Diol\LaravelMailer;

use Diol\LaravelMailer\Message as MessageExt;
use Illuminate\Mail\MailServiceProvider;
use Config;

/**
 * Class ServiceProvider
 * @package Diol\LaravelMailer
 */
class ServiceProvider extends MailServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('diol/laravel-mailer');

        $this->initConfig();

        $this->registerEvents();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $me = $this;

        $this->app->bindShared('mailer', function ($app) use ($me) {
            $me->registerSwiftMailer();

            // Once we have create the mailer instance, we will set a container instance
            // on the mailer. This allows us to resolve mailer classes via containers
            // for maximum testability on said classes instead of passing Closures.
            $mailer = new Mailer($app['view'], $app['swift.mailer'], $app['events']);

            $this->setMailerDependencies($mailer, $app);

            // If a "from" address is set, we will set it on the mailer so that all mail
            // messages sent by the applications will utilize the same "from" address
            // on each one, which makes the developer's life a lot more convenient.
            $from = $app['config']['mail.from'];

            if (is_array($from) && isset($from['address'])) {
                $mailer->alwaysFrom($from['address'], $from['name']);
            }

            // Here we will determine if the mailer should be in "pretend" mode for this
            // environment, which will simply write out e-mail to the logs instead of
            // sending it over the web, which is useful for local dev environments.
            $pretend = $app['config']->get('mail.pretend', false);

            $mailer->pretend($pretend);

            return $mailer;
        });
    }

    /**
     * Register events for mailer.
     */
    private function registerEvents()
    {
        \Event::listen('mailer.sending', function ($message) {
            if (!app('mailer')->isPretending()) {
                app('log')->info('Sending mail: ' . new MessageExt($message));
            }
        });
    }

    /**
     * Init configurations for mailer.
     */
    private function initConfig()
    {
        $this->app->extend('mailer', function (Mailer $mailer) {
            $config = Config::get('laravel-mailer::config');

            $mailer->setBcc(array_get($config, 'blind_copy'));
            $mailer->setSubstitutedTo(array_get($config, 'substituted_to'));
            $mailer->setReturnPath(array_get($config, 'return_path'));
            $mailer->setReplyTo(array_get($config, 'reply_to'));

            return $mailer;
        });
    }
}
