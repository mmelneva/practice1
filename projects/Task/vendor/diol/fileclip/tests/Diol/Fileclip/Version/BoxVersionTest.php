<?php
namespace Diol\Fileclip\Version;

use \Mockery as m;

class BoxVersionTest extends \PHPUnit_Framework_TestCase
{
    public function testModify()
    {
        $image = m::mock('Imagine\Image\ImageInterface');
        $image->shouldReceive('thumbnail')->andReturn('modified');

        $boxVersion = new BoxVersion(100, 100);
        $this->assertEquals('modified', $boxVersion->modify($image, m::mock('Imagine\Image\ImagineInterface')));
    }
}
