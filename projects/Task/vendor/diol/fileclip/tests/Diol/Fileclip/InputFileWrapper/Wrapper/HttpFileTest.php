<?php
namespace Diol\Fileclip\InputFileWrapper\Wrapper;

use Diol\Fileclip\support\FilesystemTestTools;

class HttpFileTest extends \PHPUnit_Framework_TestCase
{
    use FilesystemTestTools;

    private $testWrapper;

    public function setUp()
    {
        parent::setUp();
        $this->recreateTestStorage();
        $this->testWrapper = new HttpFile('http://localhost:3000/tests/fixtures/English%20Castle.jpg');
    }

    public function tearDown()
    {
        $this->removeTestStorage();
    }

    public function testIsValid()
    {
        $this->assertTrue($this->testWrapper->isValid());

        $invalidWrapper = new HttpFile('http://localhost:3000/tests/fixtures/English%20Castle.png');
        $this->assertFalse($invalidWrapper->isValid());
    }

    public function testGetName()
    {
        $wrapper = new HttpFile('http://localhost:65087/%D0%98%D0%B7%D0%BE%D0%B1%D1%80%D0%B0%D0%B6%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%BA%D0%B8%D1%80%D0%B8%D0%BB%D0%BB%D0%B8%D1%86%D0%B0.png');
        $this->assertEquals('Изображение кириллица.png', $wrapper->getName());
        $this->assertEquals('Изображение кириллица', $wrapper->getName(false));
    }

    public function testGetNameWrongLocale()
    {
        setlocale(LC_ALL, 'C');
        $wrapper = new HttpFile('http://localhost:65087/%D0%98%D0%B7%D0%BE%D0%B1%D1%80%D0%B0%D0%B6%D0%B5%D0%BD%D0%B8%D0%B5%20%D0%BA%D0%B8%D1%80%D0%B8%D0%BB%D0%BB%D0%B8%D1%86%D0%B0.png');
        $this->assertEquals('Изображение кириллица.png', $wrapper->getName());
        $this->assertEquals('Изображение кириллица', $wrapper->getName(false));
    }

    public function testGetExtension()
    {
        $this->assertEquals('jpeg', $this->testWrapper->getExtension());
    }

    public function testSave()
    {
        $this->testWrapper->save($this->getTestStoragePath(), '/saved.jpg');

        $saved = $this->getTestStoragePath() . '/saved.jpg';
        $this->assertFileExists($saved);
        $this->assertEquals(filesize($this->getTestFixturesPath() . '/English Castle.jpg'), filesize($saved));
    }
}
