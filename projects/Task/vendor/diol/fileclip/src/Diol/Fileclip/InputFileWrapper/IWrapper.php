<?php
namespace Diol\Fileclip\InputFileWrapper;

/**
 * Interface IWrapper
 * Wrapper to wrap an input file
 * @package Diol\Fileclip\InputFileWrapper
 */
interface IWrapper
{
    /**
     * Check if the file is valid
     * @return bool
     */
    public function isValid();

    /**
     * Save the file to directory with name
     * @param $dir - path directory
     * @param $fileName - name
     */
    public function save($dir, $fileName);

    /**
     * Get file extension
     * @return string
     */
    public function getExtension();

    /**
     * Get original file name
     * @param boolean $withExtension
     * @return string
     */
    public function getName($withExtension = true);
}
