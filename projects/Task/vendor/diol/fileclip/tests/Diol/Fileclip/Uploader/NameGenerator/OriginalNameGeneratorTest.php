<?php namespace Diol\Fileclip\Uploader\NameGenerator;

use Mockery as m;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;

/**
 * Class OriginalNameGeneratorTes
 * @package Diol\Fileclip\Uploader\NameGenerator
 */
class OriginalNameGeneratorTest extends \PHPUnit_Framework_TestCase
{
    private $tempDirectoryMock;

    public function setUp()
    {
        vfsStreamWrapper::register();
        $this->tempDirectoryMock = vfsStream::newDirectory('temp');
        vfsStreamWrapper::setRoot($this->tempDirectoryMock);
    }

    public function testGeneratedName()
    {
        $this->tempDirectoryMock->addChild(vfsStream::newFile('test_file.txt'));
        $this->tempDirectoryMock->addChild(vfsStream::newFile('test_file_1.txt'));

        $uploader = m::mock('Diol\Fileclip\Uploader\Uploader');
        $fileWrapper = m::mock('Diol\Fileclip\InputFileWrapper\IWrapper');

        $fileWrapper->shouldReceive('getName')->with(false)->andReturn('test_file');
        $fileWrapper->shouldReceive('getExtension')->andReturn('txt');

        $uploader->shouldReceive('getAbsoluteStoragePath')->with('test_file.txt')->andReturn(
            vfsStream::url('temp/test_file.txt')
        );

        $uploader->shouldReceive('getAbsoluteStoragePath')->with('test_file_1.txt')->andReturn(
            vfsStream::url('temp/test_file_1.txt')
        );

        $uploader->shouldReceive('getAbsoluteStoragePath')->with('test_file_2.txt')->andReturn(
            vfsStream::url('temp/test_file_2.txt')
        );

        $generator = new OriginalNameGenerator();
        $name = $generator->generateName($uploader, $fileWrapper);

        $this->assertEquals('test_file_2.txt', $name);
    }
}

