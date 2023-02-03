<?php namespace Diol\FileclipExifTests\integration;

use Diol\FileclipExif\ExifDataWriter;
use Diol\FileclipExifTests\models\Page;
use Diol\FileclipExifTests\models\Product;
use Diol\FileclipExifTests\support\ExifTestReader;

class ModelsWithCustomRulesTest extends IntegrationTestCase
{
    /**
     * @after
     */
    public function deletePages()
    {
        foreach (Page::all() as $page) {
            $page->delete();
        }
    }

    /**
     * @after
     */
    public function deleteProducts()
    {
        foreach (Product::all() as $product) {
            $product->delete();
        }
    }


    public function testCustomExifRule()
    {
        $page = Page::create(['name' => 'привет', 'image_file' => __DIR__ . '/../data/fixtures/photo.jpg']);
        $file = $page->getAttachment('image')->getAbsolutePath();
        $exifData = ExifTestReader::readExifData($file);

        $this->assertEquals('привет!', $exifData[ExifDataWriter::TAG_DESCRIPTION]);
        $this->assertEquals('привет', $exifData[ExifDataWriter::TAG_COMMENT]);
    }

    public function testDefaultCopyright()
    {
        $page = Page::create(['name' => 'hello', 'image_file' => __DIR__ . '/../data/fixtures/photo.jpg']);
        $file = $page->getAttachment('image')->getAbsolutePath();
        $exifData = ExifTestReader::readExifData($file);

        $this->assertEquals('Компания "Диол"', $exifData[ExifDataWriter::TAG_COPYRIGHT]);
    }

    public function testCustomCopyrightForModel()
    {
        $product = Product::create(['name' => 'hello', 'image_file' => __DIR__ . '/../data/fixtures/photo.jpg']);
        $file = $product->getAttachment('image')->getAbsolutePath();
        $exifData = ExifTestReader::readExifData($file);

        $this->assertEquals('my copyright', $exifData[ExifDataWriter::TAG_COPYRIGHT]);
    }


    public function testExifValuesWhenVersionsUpdated()
    {
        $page = Page::create(['name' => 'hello', 'image_file' => __DIR__ . '/../data/fixtures/photo.jpg']);
        $page->getAttachment('image')->updateVersions();

        $file = $page->getAttachment('image')->getAbsolutePath('thumb');
        $exifData = ExifTestReader::readExifData($file);

        $this->assertEquals('hello!', $exifData[ExifDataWriter::TAG_DESCRIPTION]);
    }
}
