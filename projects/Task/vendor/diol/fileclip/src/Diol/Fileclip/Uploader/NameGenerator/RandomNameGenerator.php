<?php
namespace Diol\Fileclip\Uploader\NameGenerator;

use Diol\Fileclip\InputFileWrapper\IWrapper;
use Diol\Fileclip\Uploader\Uploader;

/**
 * Class RandomNameGenerator
 * Names, generated by this generator are random
 * @package Diol\Fileclip\Uploader\NameGenerator
 */
class RandomNameGenerator implements INameGenerator
{
    /**
     * {@inheritDoc}
     */
    public function generateName(Uploader $uploader, IWrapper $fileWrapper)
    {
        $fileExtension = $fileWrapper->getExtension();

        do {
            $generatedFileName = str_random() . '.' . $fileExtension;
            $destination = $uploader->getAbsolutePath($generatedFileName);
        } while (file_exists($destination));
        return $generatedFileName;
    }
}