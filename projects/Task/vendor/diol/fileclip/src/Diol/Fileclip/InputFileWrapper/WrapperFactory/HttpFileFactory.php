<?php
namespace Diol\Fileclip\InputFileWrapper\WrapperFactory;

use Diol\Fileclip\InputFileWrapper\IWrapperFactory;
use Diol\Fileclip\InputFileWrapper\Wrapper\HttpFile;

/**
 * Class HttpFileFactory
 * Factory to create file wrappers, which handle files via http or https.
 * @package Diol\Fileclip\InputFileWrapper\WrapperFactory
 */
class HttpFileFactory implements IWrapperFactory
{
    /**
     * {@inheritDoc}
     */
    public function check($value)
    {
        return is_string($value) && (bool) preg_match("/^(http|https):\/\/.+/", $value);
    }

    /**
     * {@inheritDoc}
     */
    public function getWrapper($value)
    {
        return new HttpFile($value);
    }
}
