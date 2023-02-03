<?php

namespace Diol\Fileclip\Uploader\NameGenerator;

use Diol\Fileclip\InputFileWrapper\IWrapper;
use Diol\Fileclip\Uploader\Uploader;

/**
 * Class OriginalNameGenerator
 * Original file name + digit maybe
 * @package Diol\Fileclip\Uploader\NameGenerator
 */
class OriginalNameGenerator implements INameGenerator
{
    public function generateName(Uploader $uploader, IWrapper $fileWrapper)
    {
        $fileExtension = $fileWrapper->getExtension();

        $i = 0;
        do {
            $suffix = $i ? "_{$i}" : '';
            $generatedFileName = $fileWrapper->getName(false) . $suffix . '.' . $fileExtension;
            $destination = $uploader->getAbsoluteStoragePath($generatedFileName);
            $i++;
        } while (file_exists($destination));

        return $generatedFileName;
    }
}
