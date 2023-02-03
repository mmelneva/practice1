<?php
namespace Diol\Fileclip\support;

use Mockery as m;

abstract class IntegrationTestCase extends FileSystemTestCase
{
    public function setUp()
    {
        $app = new \Illuminate\Foundation\Application;
        $app->instance('app', $app);
        $app->register('Diol\Fileclip\FileclipServiceProvider');
        \Illuminate\Support\Facades\Facade::setFacadeApplication($app);

        \Illuminate\Support\Facades\Config::swap($config = m::mock('ConfigMock'));
        $config->shouldReceive('get')->with('fileclip::public_root')->andReturn(self::getTestPublicPath());
        $config->shouldReceive('get')->with('fileclip::storage_root')->andReturn(self::getTestStoragePath());
        $config->shouldReceive('get')->with('fileclip::name_generator', 'through_numbering')
            ->andReturn('through_numbering');

        $config->shouldReceive('get')->with('fileclip::filename_prefix')->andReturn('test_prefix');
        $config->shouldReceive('get')->with('fileclip::imagine_driver', m::anyOf('gd', 'imagick', 'auto'))->andReturn('auto');

        \Illuminate\Support\Facades\Log::swap($log = m::mock('LogMock'));
        $log->shouldReceive('warning');

        parent::setUp();
    }

    public function tearDown()
    {
        m::close();
    }
}
