<?php
namespace Diol\Fileclip\InputFileWrapper\Wrapper;

use Diol\Fileclip\support\FilesystemTestTools;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SymfonyUploadedFileTest extends \PHPUnit_Framework_TestCase
{
    use FilesystemTestTools;

    private $testFile;
    /** @var  SymfonyUploadedFile */
    private $wrapper;
    /** @var  SymfonyUploadedFile */
    private $invalidWrapper;

    public function setUp()
    {
        $this->recreateTestStorage();

        $this->testFile = $this->getTestStoragePath() . '/test.txt';
        file_put_contents($this->testFile, 'test123');
        $this->wrapper = new SymfonyUploadedFile(new UploadedFile($this->testFile, 'test.xxx', null, null, null, true));
        $this->invalidWrapper = new SymfonyUploadedFile(new UploadedFile($this->testFile, 'test.xxx', null, null, 666));
    }

    public function tearDown()
    {
        $this->removeTestStorage();
    }

    public function testIsValid()
    {
        $this->assertTrue($this->wrapper->isValid());
        $this->assertFalse($this->invalidWrapper->isValid());
    }

    public function testGetName()
    {
        $this->assertEquals('test.xxx', $this->wrapper->getName());
        $this->assertEquals('test', $this->wrapper->getName(false));
    }

    public function testGetNameCyrillicWrongLocale()
    {
        setlocale(LC_ALL, 'C');
        $this->wrapper = new SymfonyUploadedFile(new UploadedFile($this->testFile, 'Иванов И.И..xxx', null, null, null, true));
        $this->assertEquals('Иванов И.И..xxx', $this->wrapper->getName());
        $this->assertEquals('Иванов И.И.', $this->wrapper->getName(false));
    }

    public function testGetExtension()
    {
        $this->assertEquals('xxx', $this->wrapper->getExtension());
    }

    public function testSave()
    {
        $this->wrapper->save($this->getTestStoragePath() . '/1/2/3/4', 'test.xxx');
        $newPath = $this->getTestStoragePath() . '/1/2/3/4/test.xxx';

        $this->assertFileExists($newPath);
        $this->assertEquals('test123', file_get_contents($newPath));
        $this->assertFileNotExists($this->getTestStoragePath() . '/test.txt');
    }
}
