<?php
namespace Diol\Fileclip\InputFileWrapper;

/**
 * Interface IWrapperFactory
 * Factory to create different wrappers
 * @package Diol\Fileclip\InputFileWrapper
 */
interface IWrapperFactory
{
    /**
     * Check if value is valid to create exact wrapper
     * @param $value
     * @return bool
     */
    public function check($value);

    /**
     * Create exact wrapper with value
     * @param $value
     * @return IWrapper
     */
    public function getWrapper($value);
}
