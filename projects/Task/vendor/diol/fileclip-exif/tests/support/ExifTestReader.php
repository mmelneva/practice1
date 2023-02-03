<?php namespace Diol\FileclipExifTests\support;

use Diol\FileclipExif\ExifDataWriter;

class ExifTestReader
{
    public static function readExifData($filePath)
    {
        $exifData = exif_read_data($filePath);

        return [
            ExifDataWriter::TAG_DESCRIPTION => $exifData['ImageDescription'],
            ExifDataWriter::TAG_COPYRIGHT => $exifData['Copyright'],
            ExifDataWriter::TAG_COMMENT => self::getUserComment($exifData),
        ];
    }

    private static function getUserComment($exifData)
    {
        return mb_convert_encoding(trim(str_replace('UNICODE', '', $exifData['UserComment'])), 'utf-8', 'utf-16le');
    }
}
