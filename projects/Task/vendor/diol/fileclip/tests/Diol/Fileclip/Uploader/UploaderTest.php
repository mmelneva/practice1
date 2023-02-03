<?php
namespace Diol\Fileclip\Uploader;

use Diol\Fileclip\support\FileSystemTestCase;
use Diol\Fileclip\Version\DefaultVersion;
use Mockery as m;

class UploaderTest extends FileSystemTestCase
{
    /** @var  \Mockery\MockInterface */
    private $imagine;
    /** @var  \Mockery\MockInterface */
    private $nameGenerator;
    /** @var  \Mockery\MockInterface */
    private $versions;
    /** @var  Uploader */
    private $uploader;

    public function setUp()
    {
        parent::setUp();

        $this->imagine = m::mock('Imagine\Image\ImagineInterface');
        $this->nameGenerator = m::mock('Diol\Fileclip\Uploader\NameGenerator\INameGenerator');
        $this->versions = [
            'default' => new DefaultVersion(),
            'x' => m::mock('Diol\Fileclip\Version\IVersion'),
            'y' => m::mock('Diol\Fileclip\Version\IVersion'),
        ];
        $this->uploader = new Uploader(
            self::getTestPublicPath(),
            self::getTestStoragePath(),
            $this->nameGenerator,
            'files',
            $this->imagine,
            $this->versions
        );
    }

    /**
     * @expectedException \Diol\Fileclip\Uploader\IncorrectVersion
     */
    public function testIncorrectVersion()
    {
        new Uploader(
            self::getTestPublicPath(),
            self::getTestStoragePath(),
            $this->nameGenerator,
            'files',
            $this->imagine,
            [m::mock('Diol\Fileclip\Version\IVersion'), 100]
        );
    }

    public function testGetImagine()
    {
        $this->assertSame(
            $this->imagine,
            $this->uploader->getImagine()
        );
    }

    public function testGetNameGenerator()
    {
        $this->assertSame(
            $this->nameGenerator,
            $this->uploader->getNameGenerator()
        );
    }

    public function testGetVersionHandlers()
    {
        $this->assertSame(
            $this->versions,
            $this->uploader->getVersionHandlers()
        );
    }

    public function testGetVersionHandler()
    {
        foreach ($this->versions as $vKey => $vHandler) {
            $this->assertSame(
                $vHandler,
                $this->uploader->getVersionHandler($vKey)
            );
        }
        $this->assertNull(
            $this->uploader->getVersionHandler('unknown')
        );
    }

    public function testGetRelativePath()
    {
        $this->assertEquals(
            '/files',
            $this->uploader->getRelativePath()
        );
        $this->assertEquals(
            '/files/file.txt',
            $this->uploader->getRelativePath('file.txt')
        );
    }

    public function testGetAbsolutePath()
    {
        $this->assertEquals(
            self::getTestPublicPath() . '/files',
            $this->uploader->getAbsolutePath()
        );
        $this->assertEquals(
            self::getTestPublicPath() . '/files/file.txt',
            $this->uploader->getAbsolutePath('file.txt')
        );
    }

    public function testGetAbsoluteStoragePath()
    {
        $this->assertEquals(
            self::getTestStoragePath() . '/files',
            $this->uploader->getAbsoluteStoragePath()
        );
        $this->assertEquals(
            self::getTestStoragePath() . '/files/file.txt',
            $this->uploader->getAbsoluteStoragePath('file.txt')
        );
    }

    public function testRetrieveSuccess()
    {
        mkdir(self::getTestStoragePath() . '/files');
        file_put_contents(self::getTestStoragePath() . '/files/file.txt', 'test');
        $this->assertInstanceOf('Diol\Fileclip\Uploader\Stored', $this->uploader->retrieve('file.txt'));
    }

    public function testRetrieveFail()
    {
        $this->assertNull($this->uploader->retrieve('not_found.txt'));
    }


    public function testStore()
    {
        $fileWrapper = m::mock('Diol\Fileclip\InputFileWrapper\IWrapper');
        $fileWrapper->shouldReceive('isValid')->andReturn(true);

        $this->nameGenerator->shouldReceive('generateName')->with($this->uploader, $fileWrapper)
            ->andReturn('generated.name');
        $fileWrapper->shouldReceive('save')->with($this->uploader->getAbsoluteStoragePath(), 'generated.name')->once();

        $this->assertInstanceOf('Diol\Fileclip\Uploader\Stored', $this->uploader->store($fileWrapper));
    }

    public function testStoreIncorrectInput()
    {
        $fileWrapper = m::mock('Diol\Fileclip\InputFileWrapper\IWrapper');
        $fileWrapper->shouldReceive('isValid')->andReturn(false);

        $this->assertNull($this->uploader->store($fileWrapper));
    }

    public function testGetAvailableVersions()
    {
        $this->assertEquals(['default', 'x', 'y'], $this->uploader->getAvailableVersions());
    }
}
