<?php

namespace Diol\Fileclip\Uploader\NameGenerator;

use Diol\Fileclip\InputFileWrapper\IWrapper;
use Diol\Fileclip\Uploader\Uploader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class ThroughNumberingNameGenerator
 * @package Diol\Fileclip\Uploader\NameGenerator
 */
class ThroughNumberingNameGenerator implements INameGenerator
{
    private $filenamePrefix;

    /**
     * ThroughNumberingNameGenerator constructor.
     * @param $filenamePrefix
     */
    public function __construct($filenamePrefix)
    {
        if (!empty($filenamePrefix)) {
            $filenamePrefix = preg_replace('#[\.\-\s]+#', '_', $filenamePrefix) . '_';
        }

        $this->filenamePrefix = $filenamePrefix;
    }

    public function generateName(Uploader $uploader, IWrapper $fileWrapper)
    {
        $fileExtension = $fileWrapper->getExtension();

        $existedFileNames = [];
        if (file_exists($storagePath = $uploader->getAbsoluteStoragePath())) {
            $finder = Finder::create();
            $finder->in($storagePath)->files();

            /** @var SplFileInfo $file */
            foreach ($finder as $file) {
                $extension = $file->getExtension();
                $existedFileNames[] = $file->getBasename(!empty($extension) ? ".{$extension}" : null);
            }
        }
        $existedFileNames = array_flip($existedFileNames);

        $i = 1;
        do {
            $paddedNumber = str_pad_unicode($i++, 7, '0', STR_PAD_LEFT);

            $generatedBasename = $this->filenamePrefix . $paddedNumber;
            $generatedFileName = $generatedBasename . '.' . $fileExtension;

        } while (isset($existedFileNames[$generatedBasename]));

        return $generatedFileName;
    }
}
