<?php
namespace Diol\Fileclip\Uploader;

use Diol\Fileclip\support\FileSystemTestCase;
use Mockery as m;

class StoredTest extends FileSystemTestCase
{
    /** @var \Mockery\MockInterface */
    private $uploader;
    /** @var string */
    private $fileName;
    /** @var Stored */
    private $stored;

    public function setUp()
    {
        parent::setUp();
        $this->fileName = 'test.txt';
        $this->uploader = m::mock('Diol\Fileclip\Uploader\Uploader');
        $this->uploader->shouldReceive('getDefaultVersionKey')->andReturn('default');
        $this->stored = new Stored($this->uploader, $this->fileName);
    }

    public function testGetName()
    {
        $this->assertEquals('test.txt', $this->stored->getName());
    }

    public function testCreateVersions()
    {
        $versionHandlers = [
            'a' => m::mock('Diol\Fileclip\Version\IVersion'),
            'b' => m::mock('Diol\Fileclip\Version\IVersion'),
        ];
        $this->uploader->shouldReceive('getVersionHandlers')->andReturn($versionHandlers);

        $this->uploader->shouldReceive('getAbsolutePath')->with('test.txt')
            ->andReturn('pub_test.txt');
        $this->uploader->shouldReceive('getAbsolutePath')->with('a_test.txt')
            ->andReturn('pub_a_test.txt');
        $this->uploader->shouldReceive('getAbsolutePath')->with('b_test.txt')
            ->andReturn('pub_b_test.txt');

        $this->uploader->shouldReceive('getAbsoluteStoragePath')->with('test.txt')
            ->andReturn('abs_test.txt');

        $this->uploader->shouldReceive('getImagine')->andReturn($imagine = m::mock('Imagine\Image\ImagineInterface'));
        $imagine->shouldReceive('open')->with('abs_test.txt')
            ->andReturn($image = m::mock('Imagine\Image\ImageInterface'));

        $manipulator = m::mock('Imagine\Image\ManipulatorInterface');

        $versionHandlers['a']->shouldReceive('modify')->with($image, $imagine)->once()->andReturn($manipulator);
        $versionHandlers['b']->shouldReceive('modify')->with($image, $imagine)->once()->andReturn($manipulator);

        $versionHandlers['a']->shouldReceive('getOptions')->andReturn(['quality' => 100]);
        $versionHandlers['b']->shouldReceive('getOptions')->andReturn(['quality' => 100]);

        $manipulator->shouldReceive('save')->with('pub_a_test.txt', ['quality' => 100])->once();
        $manipulator->shouldReceive('save')->with('pub_b_test.txt', ['quality' => 100])->once();

        $this->stored->createVersions();
    }

    public function testCreateVersionFromNotImage()
    {
        file_put_contents($this->getTestStoragePath() . '/test.txt', 'test');
        $absPath = $this->getTestStoragePath() . '/test.txt';
        $pubPath = $this->getTestPublicPath() . '/test.txt';


        $versionHandlers = [
            'a' => m::mock('Diol\Fileclip\Version\IVersion'),
            'b' => m::mock('Diol\Fileclip\Version\IVersion'),
        ];
        $this->uploader->shouldReceive('getVersionHandlers')->andReturn($versionHandlers);
        
        $this->uploader->shouldReceive('getAbsolutePath')->with('test.txt')->andReturn($pubPath);
        $this->uploader->shouldReceive('getAbsoluteStoragePath')->with('test.txt')->andReturn($absPath);

        $this->uploader->shouldReceive('getImagine')->andReturn($imagine = m::mock('Imagine\Image\ImagineInterface'));
        $imagine->shouldReceive('open')->with($absPath)->andThrow('Imagine\Exception\RuntimeException');

        $this->stored->createVersions();
        $this->assertFileExists($pubPath);
    }
}
