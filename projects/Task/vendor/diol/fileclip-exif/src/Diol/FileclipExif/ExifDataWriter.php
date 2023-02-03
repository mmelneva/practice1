<?php namespace Diol\FileclipExif;

use lsolesen\pel\PelDataWindow;
use lsolesen\pel\PelEntryAscii;
use lsolesen\pel\PelEntryUserComment;
use lsolesen\pel\PelException;
use lsolesen\pel\PelExif;
use lsolesen\pel\PelIfd;
use lsolesen\pel\PelJpeg;
use lsolesen\pel\PelTag;
use lsolesen\pel\PelTiff;

/**
 * Class ExifDataWriter
 * Writer for exif data to images.
 *
 * @package Diol\FileclipExif
 */
class ExifDataWriter
{
    const TAG_DESCRIPTION = 'description';
    const TAG_COPYRIGHT = 'copyright';
    const TAG_COMMENT = 'comment';

    /**
     * @var array
     */
    private $exifData;


    /**
     * Create data writer with data.
     *
     * @param array $exifData
     */
    public function __construct(array $exifData = [])
    {
        $this->exifData = $exifData;
    }


    /**
     * Write exif data to file.
     *
     * @param $filePath
     * @param null $newFilePath - if null, the source file will be overwritten.
     * @throws \lsolesen\pel\PelInvalidDataException
     * @throws \lsolesen\pel\PelJpegInvalidMarkerException
     */
    public function writeTo($filePath, $newFilePath = null)
    {
        if (is_null($newFilePath)) {
            $newFilePath = $filePath;
        }

        if (file_exists($filePath)) {
            $data = new PelDataWindow(file_get_contents($filePath));

            try {
                $valid = PelJpeg::isValid($data);
            } catch (PelException $e) {
                $valid = false;
            }

            if ($valid) {
                $jpeg = new PelJpeg();
                $jpeg->load($data);

                $exif = $jpeg->getExif();

                if ($exif == null) {
                    $exif = new PelExif();
                    $jpeg->setExif($exif);

                    $tiff = new PelTiff();
                    $exif->setTiff($tiff);
                } else {
                    $tiff = $exif->getTiff();
                }


                // Get ifd
                $ifd0 = new PelIfd(PelIfd::IFD0);
                $tiff->setIfd($ifd0);


                $this->addFD0Entry(
                    $ifd0,
                    PelTag::IMAGE_DESCRIPTION,
                    array_get($this->exifData, self::TAG_DESCRIPTION)
                );

                $this->addFD0Entry(
                    $ifd0,
                    PelTag::COPYRIGHT,
                    array_get($this->exifData, self::TAG_COPYRIGHT)
                );

                $this->addFD0Entry($ifd0, PelTag::SOFTWARE, '');


                // Из-за особенностей хранения юникода в exif нужно сделать так
                $comment = new PelEntryUserComment(
                    mb_convert_encoding(array_get($this->exifData, self::TAG_COMMENT), 'utf-16le', 'utf-8'),
                    'UNICODE'
                );
                $exif_ifd = new PelIfd(PelIfd::EXIF);
                $exif_ifd->addEntry($comment);
                $ifd0->addSubIfd($exif_ifd);


                $jpeg->saveFile($newFilePath);
            }
        }
    }


    /**
     * Add FD0 Entry - it's TIFF section.
     *
     * @param PelIfd $ifd0
     * @param $tag
     * @param $value
     * @throws \lsolesen\pel\PelInvalidDataException
     */
    private function addFD0Entry(PelIfd $ifd0, $tag, $value)
    {
        // Странно что в ASCII, но только так и работает
        // Походу это просто сырые данные с нулевым символом на конце
        $entry = new PelEntryAscii($tag, $value);
        $ifd0->addEntry($entry);
    }
}
