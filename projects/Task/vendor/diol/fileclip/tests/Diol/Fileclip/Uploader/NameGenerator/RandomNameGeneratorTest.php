<?php
namespace Diol\Fileclip\Uploader\NameGenerator;

use Mockery as m;

class RandomNameGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGeneratedName()
    {
        $uploader = m::mock('Diol\Fileclip\Uploader\Uploader');
        $fileWrapper = m::mock('Diol\Fileclip\InputFileWrapper\IWrapper');
        $fileWrapper->shouldReceive('getExtension')->andReturn('txt');
        $uploader->shouldReceive('getAbsolutePath')->with(m::any())->andReturn(null);

        $generator = new RandomNameGenerator();
        $name1 = $generator->generateName($uploader, $fileWrapper);
        $name2 = $generator->generateName($uploader, $fileWrapper);

        $this->assertNotEquals($name1, $name2);
        $this->assertEquals('txt', substr($name1, -3));
        $this->assertEquals('txt', substr($name2, -3));
    }
}
