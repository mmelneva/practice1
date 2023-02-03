<?php
namespace Diol\Fileclip\integration;

use Diol\Fileclip\Eloquent\Glue;
use Diol\Fileclip\support\models\DummyModel;
use Diol\Fileclip\support\IntegrationTestCase;

class ActualModelTest extends IntegrationTestCase
{
    /** @var DummyModel */
    private $dummyModel;
    private $absoluteFoo;
    private $absoluteBar;

    public function setUp()
    {
        parent::setUp();

        include __DIR__ . '/../support/init_database.php';
        DummyModel::boot();

        $this->dummyModel = new DummyModel();
    }

    private function storeCorrectly()
    {
        file_put_contents($this->getTestPublicPath() . '/test.txt', 'test');
        $fixturePath = $this->getTestPublicPath() . '/test.txt';

        $this->dummyModel->fill(['foo_file' => $fixturePath, 'bar_file' => $fixturePath]);
        $this->dummyModel->save();

        $this->absoluteFoo = $this->dummyModel->getAttachment('foo')->getAbsolutePath();
        $this->absoluteBar = $this->dummyModel->getAttachment('bar')->getAbsolutePath();
    }

    public function testSavedCorrectly()
    {
        $this->storeCorrectly();

        $this->assertEquals($this->getTestPublicPath() . '/files/' . $this->dummyModel->foo, $this->absoluteFoo);
        $this->assertEquals(
            '/files/' . $this->dummyModel->foo,
            $this->dummyModel->getAttachment('foo')->getRelativePath()
        );
        $this->assertEquals('test', file_get_contents($this->absoluteFoo));

        $this->assertEquals($this->getTestPublicPath() . '/files/' . $this->dummyModel->bar, $this->absoluteBar);
        $this->assertEquals(
            '/files/' . $this->dummyModel->bar,
            $this->dummyModel->getAttachment('bar')->getRelativePath()
        );
        $this->assertEquals('test', file_get_contents($this->absoluteBar));
    }

    public function testRemoveFields()
    {
        $this->storeCorrectly();

        $this->dummyModel->fill(['foo_remove' => true, 'bar_remove' => true]);
        $this->dummyModel->save();

        $this->assertFileNotExists($this->absoluteFoo);
        $this->assertNull($this->dummyModel->getAttachment('foo')->getAbsolutePath());
        $this->assertNull($this->dummyModel->getAttachment('foo')->getRelativePath());
        $this->assertNull($this->dummyModel->foo);

        $this->assertFileExists($this->absoluteBar);
        $this->assertNotNull($this->dummyModel->getAttachment('bar')->getAbsolutePath());
        $this->assertNotNull($this->dummyModel->getAttachment('bar')->getRelativePath());
        $this->assertNotNull($this->dummyModel->bar);
    }

    public function testDeleteModel()
    {
        $this->storeCorrectly();

        $this->dummyModel->delete();
        $this->assertFileNotExists($this->absoluteFoo);
        $this->assertFileNotExists($this->absoluteBar);
    }

    public function testStoreNullInput()
    {
        $dummyModel = new DummyModel();
        $dummyModel->fill(['foo_file' => null]);
        $dummyModel->save();
        $this->assertNull($dummyModel->foo);
    }

    public function testStoreIncorrectInput()
    {
        $dummyModel = new DummyModel();
        $dummyModel->fill(['foo_file' => 'incorrect']);
        $dummyModel->save();
        $this->assertNull($dummyModel->foo);
    }

    public function testGetIncorrectAttachment()
    {
        $this->assertNull($this->dummyModel->getAttachment('not_attachment'));
    }

    public function testGetAttachmentFields()
    {
        $this->assertEquals(['foo', 'bar'], DummyModel::getAttachmentFields());
    }


    public function testSaveFileWithSameNameWhichWasDeleted()
    {
        $this->storeCorrectly();
        $fileInStorage = $this->getTestStoragePath() . '/files/' . $this->dummyModel->foo;
        unlink($fileInStorage);

        $fixturePath = $this->getTestPublicPath() . '/test.txt';
        $this->dummyModel->fill(['foo_file' => $fixturePath]);
        $this->dummyModel->save();

        $this->assertEquals('test', file_get_contents($this->absoluteBar));
    }
}
