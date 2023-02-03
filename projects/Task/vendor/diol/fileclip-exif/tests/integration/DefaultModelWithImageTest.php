<?php namespace Diol\FileclipExifTests\integration;

use Diol\FileclipExif\ExifDataWriter;
use Diol\FileclipExifTests\models\Node;
use Diol\FileclipExifTests\support\ExifTestReader;
use Illuminate\Support\Facades\Config;

class DefaultModelWithImageTest extends IntegrationTestCase
{
    /**
     * @after
     */
    public function deleteNodes()
    {
        foreach (Node::all() as $node) {
            $node->delete();
        }
    }

    public function testDefaultExifValues()
    {
        $node = new Node(
            [
                'name' => 'Имя',
                'meta_title' => 'Тайтл',
                'header' => 'Заголовок',
                'image_file' => __DIR__ . '/../data/fixtures/photo.jpg',
            ]
        );
        $node->save();

        $file = $node->getAttachment('image')->getAbsolutePath();
        $exifData = ExifTestReader::readExifData($file);

        $this->assertEquals('Заголовок', $exifData[ExifDataWriter::TAG_DESCRIPTION]);
        $this->assertEquals('Тайтл', $exifData[ExifDataWriter::TAG_COMMENT]);
        $this->assertEquals('Компания "Диол"', $exifData[ExifDataWriter::TAG_COPYRIGHT]);
    }


    public function testDefaultExifValuesWhenTitleEmpty()
    {
        $node = new Node(
            [
                'name' => 'Имя',
                'meta_title' => '',
                'header' => 'Заголовок',
                'image_file' => __DIR__ . '/../data/fixtures/photo.jpg',
            ]
        );
        $node->save();

        $file = $node->getAttachment('image')->getAbsolutePath();
        $exifData = ExifTestReader::readExifData($file);

        $this->assertEquals('Заголовок', $exifData[ExifDataWriter::TAG_DESCRIPTION]);
        $this->assertEquals('Имя', $exifData[ExifDataWriter::TAG_COMMENT]);
        $this->assertEquals('Компания "Диол"', $exifData[ExifDataWriter::TAG_COPYRIGHT]);
    }


    public function testDefaultExifValuesWhenHeaderEmpty()
    {
        $node = new Node(
            [
                'name' => 'Имя',
                'meta_title' => 'Тайтл',
                'header' => '',
                'image_file' => __DIR__ . '/../data/fixtures/photo.jpg',
            ]
        );
        $node->save();

        $file = $node->getAttachment('image')->getAbsolutePath();
        $exifData = ExifTestReader::readExifData($file);

        $this->assertEquals('Имя', $exifData[ExifDataWriter::TAG_DESCRIPTION]);
        $this->assertEquals('Тайтл', $exifData[ExifDataWriter::TAG_COMMENT]);
        $this->assertEquals('Компания "Диол"', $exifData[ExifDataWriter::TAG_COPYRIGHT]);
    }


    public function testDefaultExifValuesForVersion()
    {
        $node = new Node(
            [
                'name' => 'Имя',
                'meta_title' => 'Тайтл',
                'header' => 'Заголовок',
                'image_file' => __DIR__ . '/../data/fixtures/photo.jpg',
            ]
        );
        $node->save();

        $file = $node->getAttachment('image')->getAbsolutePath('thumb');
        $exifData = ExifTestReader::readExifData($file);

        $this->assertEquals('Заголовок', $exifData[ExifDataWriter::TAG_DESCRIPTION]);
        $this->assertEquals('Тайтл', $exifData[ExifDataWriter::TAG_COMMENT]);
        $this->assertEquals('Компания "Диол"', $exifData[ExifDataWriter::TAG_COPYRIGHT]);
    }


    public function testChangeDefaultCopyright()
    {
        Config::set('fileclip-exif::copyright', 'my copy');

        $node = new Node(
            [
                'name' => 'Имя',
                'meta_title' => 'Тайтл',
                'header' => 'Заголовок',
                'image_file' => __DIR__ . '/../data/fixtures/photo.jpg',
            ]
        );
        $node->save();

        $file = $node->getAttachment('image')->getAbsolutePath();
        $exifData = ExifTestReader::readExifData($file);

        $this->assertEquals('my copy', $exifData[ExifDataWriter::TAG_COPYRIGHT]);
    }


    public function testExifValuesWhenVersionsUpdated()
    {
        $node = new Node(
            [
                'name' => 'Имя',
                'meta_title' => 'Тайтл',
                'header' => 'Заголовок',
                'image_file' => __DIR__ . '/../data/fixtures/photo.jpg',
            ]
        );
        $node->save();
        $node->getAttachment('image')->updateVersions();

        $file = $node->getAttachment('image')->getAbsolutePath('thumb');
        $exifData = ExifTestReader::readExifData($file);

        $this->assertEquals('Заголовок', $exifData[ExifDataWriter::TAG_DESCRIPTION]);
        $this->assertEquals('Тайтл', $exifData[ExifDataWriter::TAG_COMMENT]);
        $this->assertEquals('Компания "Диол"', $exifData[ExifDataWriter::TAG_COPYRIGHT]);
    }


    public function testUpdateExifWhenDataChanged()
    {
        $node = new Node(
            [
                'name' => 'Имя',
                'meta_title' => 'Тайтл',
                'header' => 'Заголовок',
                'image_file' => __DIR__ . '/../data/fixtures/photo.jpg',
            ]
        );
        $node->save();

        $node->header = 'Новый заголовок';
        $node->save();

        $file = $node->getAttachment('image')->getAbsolutePath();
        $exifData = ExifTestReader::readExifData($file);

        $this->assertEquals('Новый заголовок', $exifData[ExifDataWriter::TAG_DESCRIPTION]);
        $this->assertEquals('Тайтл', $exifData[ExifDataWriter::TAG_COMMENT]);
        $this->assertEquals('Компания "Диол"', $exifData[ExifDataWriter::TAG_COPYRIGHT]);
    }

    public function testWhenCopyrightInConfigHasIncorrectData()
    {
        Config::set('fileclip-exif::copyright', []);

        $node = new Node(
            [
                'name' => 'Имя',
                'meta_title' => 'Тайтл',
                'header' => 'Заголовок',
                'image_file' => __DIR__ . '/../data/fixtures/photo.jpg',
            ]
        );
        $node->save();

        $file = $node->getAttachment('image')->getAbsolutePath();
        $exifData = ExifTestReader::readExifData($file);

        $this->assertEquals('', $exifData[ExifDataWriter::TAG_COPYRIGHT]);
    }


    public function testUpdateExifData()
    {
        $node = new Node(
            [
                'name' => 'Имя',
                'meta_title' => 'Тайтл',
                'header' => 'Заголовок',
                'image_file' => __DIR__ . '/../data/fixtures/photo.jpg',
            ]
        );
        $node->save();
        $node->header = '';
        $node->updateExifData();

        $file = $node->getAttachment('image')->getAbsolutePath();
        $exifData = ExifTestReader::readExifData($file);

        $this->assertEquals('Имя', $exifData[ExifDataWriter::TAG_DESCRIPTION]);
    }

    public function testwithoutUpdatingExifData()
    {
        $node = new Node(
            [
                'name' => 'Имя',
                'meta_title' => 'Тайтл',
                'header' => 'Заголовок',
                'image_file' => __DIR__ . '/../data/fixtures/photo.jpg',
            ]
        );
        $node->save();

        Node::withoutUpdatingExifData(function () use ($node) {
            $node->header = 'Новый заголовок';
            $node->save();
        });

        $file = $node->getAttachment('image')->getAbsolutePath();
        $exifData = ExifTestReader::readExifData($file);

        $this->assertEquals('Заголовок', $exifData[ExifDataWriter::TAG_DESCRIPTION]);
    }

}
