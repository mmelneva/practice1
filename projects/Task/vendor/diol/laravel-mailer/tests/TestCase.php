<?php namespace tests;

use \Mockery as m;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // reset base path to point to our package's src directory
        $app['path.base'] = __DIR__ . '/../src';

        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', array(
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ));

        $app['config']->set('view.paths', [__DIR__ . '/views']);
    }

    protected function getPackageProviders()
    {
        return ['Diol\LaravelMailer\ServiceProvider'];
    }

    protected function getPackageAliases()
    {
        return ['Search' => 'Diol\LaravelMailer\Facade'];
    }
}
