<?php
namespace Diol\Fileclip\InputFileWrapper\Wrapper;

use Diol\Fileclip\InputFileWrapper\IWrapper;

/**
 * Class NullWrapper
 * Null file wrapper
 * It does not do anything
 * @package Diol\Fileclip\InputFileWrapper\Wrapper
 */
class NullWrapper implements IWrapper
{
    /**
     * {@inheritDoc}
     */
    public function isValid()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtension()
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function save($dir, $fileName)
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getName($withExtension = true)
    {
        return null;
    }
}
