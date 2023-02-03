<?php namespace Diol\Fileclip\integration;

use Diol\Fileclip\support\IntegrationTestCase;
use Diol\Fileclip\support\models\DummyModel;
use Mockery as m;

class ModelWithAttachmentHandlerTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();

        include __DIR__ . '/../support/init_database.php';
        DummyModel::boot();
    }

    public function testHandlerFinished()
    {
        $h = m::mock('Diol\Fileclip\Eloquent\AttachmentHandlerInterface');
        $h->shouldReceive('getEvent')->andReturn('finished');
        $h->shouldReceive('handle')->atLeast()->once();

        DummyModel::addCommonAttachmentHandler($h);
        DummyModel::create([]);
    }

    public function testHandlerVersionsUpdated()
    {
        $h = m::mock('Diol\Fileclip\Eloquent\AttachmentHandlerInterface');
        $h->shouldReceive('getEvent')->andReturn('versionsUpdated');
        $h->shouldReceive('handle')->atLeast()->once();

        DummyModel::addCommonAttachmentHandler($h);
        $d = DummyModel::create([]);
        $d->getAttachment('foo')->updateVersions();
    }
}
