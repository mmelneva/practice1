<?php
namespace Diol\Fileclip\Uploader\NameGenerator;

use Diol\Fileclip\InputFileWrapper\IWrapper;
use Diol\Fileclip\Uploader\Uploader;

/**
 * Interface INameGenerator
 * Interface for name generators to generate new names for files to save
 * @package Diol\Fileclip\Uploader\NameGenerator
 */
interface INameGenerator
{
    /**
     * Generate name
     * @param Uploader $uploader
     * @param IWrapper $fileWrapper
     * @return mixed
     */
    public function generateName(Uploader $uploader, IWrapper $fileWrapper);
}
