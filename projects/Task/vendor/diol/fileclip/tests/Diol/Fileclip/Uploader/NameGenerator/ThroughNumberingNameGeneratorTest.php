<?php

namespace Diol\Fileclip\Uploader\NameGenerator;

use Mockery as m;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;

/**
 * Class ThroughNumberingNameGeneratorTest
 * @package Diol\Fileclip\Uploader\NameGenerator
 */
class ThroughNumberingNameGeneratorTest extends \PHPUnit_Framework_TestCase
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
        $this->tempDirectoryMock->addChild(vfsStream::newFile('test_prefix_0000001.txt'));

        $uploader = m::mock('Diol\Fileclip\Uploader\Uploader');
        $fileWrapper = m::mock('Diol\Fileclip\InputFileWrapper\IWrapper');
        $fileWrapper->shouldReceive('getExtension')->andReturn('txt');

        $uploader->shouldReceive('getAbsoluteStoragePath')->withNoArgs()->andReturn(
            vfsStream::url('temp/')
        );

        $generator = new ThroughNumberingNameGenerator('test_prefix');
        $filename = $generator->generateName($uploader, $fileWrapper);

        $this->assertEquals($filename, 'test_prefix_0000002.txt');
        $this->assertEquals('txt', substr($filename, -3));
    }
}