<?php
namespace Diol\Fileclip\InputFileWrapper\WrapperFactory;

use Diol\Fileclip\InputFileWrapper\IWrapperFactory;
use Diol\Fileclip\InputFileWrapper\Wrapper\LocalSystemFile;

/**
 * Class LocalSystemFileFactory
 * Wrapper factory to files in local system
 * @package Diol\Fileclip\InputFileWrapper\WrapperFactory
 */
class LocalSystemFileFactory implements IWrapperFactory
{
    /**
     * {@inheritDoc}
     */
    public function check($value)
    {
        return is_string($value) && is_file($value);
    }

    /**
     * {@inheritDoc}
     */
    public function getWrapper($value)
    {
        return new LocalSystemFile($value);
    }
}
