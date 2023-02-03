<?php
namespace Diol\Fileclip\Eloquent;

use \Mockery as m;

class AttachmentQueueContainerTest extends \PHPUnit_Framework_TestCase
{
    /** @var AttachmentQueueContainer */
    private $queue;

    public function setUp()
    {
        $this->queue = new AttachmentQueueContainer();
    }

    public function testInitial()
    {
        $this->assertEquals(
            [],
            $this->queue->getDeleteQueue(),
            "Delete queue should be empty"
        );
        $this->assertEquals(
            [],
            $this->queue->getVersionQueue(),
            "Version queue should be empty"
        );
    }

    public function testDeleteQueue()
    {
        $this->queue->addToDeleteQueue('3');
        $this->queue->addToDeleteQueue('4');
        $this->assertEquals([3, 4], $this->queue->getDeleteQueue());

        $this->queue->clearDeleteQueue();
        $this->assertEquals([], $this->queue->getDeleteQueue());
    }

    public function testVersionQueue()
    {
        $this->queue->addToVersionQueue($s1 = m::mock('Diol\Fileclip\Uploader\Stored'));
        $this->queue->addToVersionQueue($s2 = m::mock('Diol\Fileclip\Uploader\Stored'));
        $this->assertEquals([$s1, $s2], $this->queue->getVersionQueue());

        $this->queue->clearVersionQueue();
        $this->assertEquals([], $this->queue->getVersionQueue());
    }
}
