<?php namespace Diol\FileclipExifTests\integration;

use Diol\FileclipExifTests\TestCase;
use Illuminate\Support\Facades\Config;

abstract class IntegrationTestCase extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->configure();
    }

    protected function configure()
    {
        Config::set('fileclip::public_root', __DIR__ . '/../data/public_root');
        Config::set('fileclip::storage_root', __DIR__ . '/../data/storage_root');

        $artisan = $this->app->make('artisan');

        // Call migrations specific to our tests, e.g. to seed the db.
        $artisan->call('migrate', ['--database' => 'testbench', '--path' => '../tests/migrations']);
    }
}
