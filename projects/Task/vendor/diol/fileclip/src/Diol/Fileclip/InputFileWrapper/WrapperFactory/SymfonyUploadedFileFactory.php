<?php
namespace Diol\Fileclip\InputFileWrapper\WrapperFactory;

use Diol\Fileclip\InputFileWrapper\IWrapperFactory;
use Diol\Fileclip\InputFileWrapper\Wrapper\SymfonyUploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class SymfonyUploadedFileFactory
 * Wrapper factory for symfony uploaded files
 * @package Diol\Fileclip\InputFileWrapper\WrapperFactory
 */
class SymfonyUploadedFileFactory implements IWrapperFactory
{
    /**
     * {@inheritDoc}
     */
    public function check($value)
    {
        return $value instanceof UploadedFile;
    }

    /**
     * {@inheritDoc}
     */
    public function getWrapper($value)
    {
        return new SymfonyUploadedFile($value);
    }
}
