<?php
namespace Diol\Fileclip\integration;

use Diol\Fileclip\support\IntegrationTestCase;
use Diol\Fileclip\support\models\DummyImageModel;

class ImageModelTest extends IntegrationTestCase
{
    /** @var DummyImageModel */
    private $dummyModel;

    public function setUp()
    {
        parent::setUp();

        include __DIR__ . '/../support/init_database.php';
        DummyImageModel::boot();

        $this->dummyModel = new DummyImageModel();
    }

    private function storeCorrect()
    {
        $this->dummyModel->fill(['image_file' => $this->getTestFixturesPath() . '/English Castle.jpg']);
        $this->dummyModel->save();
    }


    private function removeCorrect()
    {
        $this->dummyModel->fill(['image_remove' => true]);
        $this->dummyModel->save();
    }

    public function testVersions()
    {
        $this->storeCorrect();

        $this->assertFileExists(self::getTestStoragePath() . '/images/' . $this->dummyModel->image);
        $this->assertFileExists(self::getTestPublicPath() . '/images/thumb_' . $this->dummyModel->image);

        $this->assertEquals(
            self::getTestPublicPath() . '/images/' . $this->dummyModel->image,
            $this->dummyModel->getAttachment('image')->getAbsolutePath()
        );
        $this->assertEquals(
            self::getTestPublicPath() . '/images/thumb_' . $this->dummyModel->image,
            $this->dummyModel->getAttachment('image')->getAbsolutePath('thumb')
        );

        $this->assertEquals(
            '/images/' . $this->dummyModel->image,
            $this->dummyModel->getAttachment('image')->getRelativePath()
        );
        $this->assertEquals(
            '/images/thumb_' . $this->dummyModel->image,
            $this->dummyModel->getAttachment('image')->getRelativePath('thumb')
        );
    }

    public function testGettingAvailableVersions()
    {
        $availableVersions = $this->dummyModel->getAttachment('image')->getAvailableVersions();
        $this->assertEquals(['thumb', 'default'], $availableVersions);
    }

    public function testGettingExistingVersions()
    {
        $this->storeCorrect();
        $availableVersions = $this->dummyModel->getAttachment('image')->getExistingVersions();
        $this->assertEquals(['thumb', 'default'], $availableVersions);

        $this->removeCorrect();
        $availableVersions = $this->dummyModel->getAttachment('image')->getExistingVersions();
        $this->assertCount(0, $availableVersions);
    }

    public function testDeleteVersionsOnDelete()
    {
        $this->storeCorrect();
        $this->dummyModel->delete();
        $this->assertFileNotExists(self::getTestStoragePath() . '/images/' . $this->dummyModel->image);
        $this->assertFileNotExists(self::getTestPublicPath() . '/images/thumb_' . $this->dummyModel->image);
    }

    public function testDeleteVersionOnUpdate()
    {
        $this->storeCorrect();
        $file1 = self::getTestStoragePath() . '/images/' . $this->dummyModel->image;
        $file2 = self::getTestPublicPath() . '/images/thumb_' . $this->dummyModel->image;
        $this->dummyModel->update(['image_remove' => true]);
        $this->assertFileNotExists($file1);
        $this->assertFileNotExists($file2);
        $this->assertNull($this->dummyModel->image);
    }

    public function testStoreIncorrectFile()
    {
        $this->dummyModel->fill(['image_file' => $this->getTestFixturesPath() . '/test.txt']);
        $this->dummyModel->save();
        $this->assertFileNotExists(self::getTestPublicPath() . '/images/thumb_' . $this->dummyModel->image);
    }
}
