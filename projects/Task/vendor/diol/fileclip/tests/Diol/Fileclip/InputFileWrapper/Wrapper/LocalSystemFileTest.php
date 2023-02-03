<?php
namespace Diol\Fileclip\InputFileWrapper\Wrapper;

use Diol\Fileclip\support\FilesystemTestTools;

class LocalSystemFileTest extends \PHPUnit_Framework_TestCase
{
    use FilesystemTestTools;

    private $testFile;
    /** @var  LocalSystemFile */
    private $wrapper;
    /** @var  LocalSystemFile */
    private $invalidWrapper;

    public function setUp()
    {
        $this->recreateTestStorage();

        $this->testFile = $this->getTestStoragePath() . '/test.txt';
        file_put_contents($this->testFile, 'test123');
        $this->wrapper = new LocalSystemFile($this->testFile);
        $this->invalidWrapper = new LocalSystemFile(null);
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
        $this->assertEquals('test.txt', $this->wrapper->getName());
        $this->assertEquals('test', $this->wrapper->getName(false));
    }

    public function testGetNameCyrillicWrongLocale()
    {
        setlocale(LC_ALL, 'C');
        $this->testFile = $this->getTestStoragePath() . '/Иванов И.И..txt';
        file_put_contents($this->testFile, 'test123');
        $this->wrapper = new LocalSystemFile($this->testFile);
        $this->assertEquals('Иванов И.И..txt', $this->wrapper->getName());
        $this->assertEquals('Иванов И.И.', $this->wrapper->getName(false));
    }

    public function testGetExtension()
    {
        $this->assertEquals('txt', $this->wrapper->getExtension());
    }

    public function testSave()
    {
        $this->wrapper->save($this->getTestStoragePath(), 'test.xxx');
        $newPath = $this->getTestStoragePath() . '/test.xxx';

        $this->assertFileExists($newPath);
        $this->assertEquals('test123', file_get_contents($newPath));
        $this->assertFileExists($this->getTestStoragePath() . '/test.txt');
    }
}
