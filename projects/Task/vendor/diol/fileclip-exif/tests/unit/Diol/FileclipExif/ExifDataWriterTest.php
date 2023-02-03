<?php namespace Diol\FileclipExifTests\unit\Diol\FileclipExif;

use Diol\FileclipExif\ExifDataWriter;
use Diol\FileclipExifTests\support\ExifTestReader;

class ExifDataWriterTest extends \PHPUnit_Framework_TestCase
{
    public function testWriteTo()
    {
        $filePath = __DIR__ . '/../../../data/fixtures/photo.jpg';
        $newFilePath = __DIR__ . '/../../../data/fixtures_output/photo.jpg';

        $writer = new ExifDataWriter([
            ExifDataWriter::TAG_DESCRIPTION => 'Описание',
            ExifDataWriter::TAG_COPYRIGHT => 'Права',
            ExifDataWriter::TAG_COMMENT => 'Комментарии',
        ]);
        $writer->writeTo($filePath, $newFilePath);

        $exifData = ExifTestReader::readExifData($newFilePath);
        $this->assertEquals('Описание', $exifData[ExifDataWriter::TAG_DESCRIPTION]);
        $this->assertEquals('Права', $exifData[ExifDataWriter::TAG_COPYRIGHT]);
        $this->assertEquals('Комментарии', $exifData[ExifDataWriter::TAG_COMMENT]);
    }

    public function testWriteToItself()
    {
        $filePath = __DIR__ . '/../../../data/fixtures/photo.jpg';
        $newFilePath = __DIR__ . '/../../../data/fixtures_output/new_photo.jpg';
        copy($filePath, $newFilePath);

        $writer = new ExifDataWriter([ExifDataWriter::TAG_DESCRIPTION => 'Описание']);
        $writer->writeTo($newFilePath);

        $exifData = ExifTestReader::readExifData($newFilePath);
        $this->assertEquals('Описание', $exifData[ExifDataWriter::TAG_DESCRIPTION]);
    }
}
