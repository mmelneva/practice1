<?php

namespace Diol\Fileclip\InputFileWrapper\Wrapper\Exception;

/**
 * Class ImpossibleToGetFileContent
 * @package Diol\Fileclip\InputFileWrapper\Wrapper\Exception
 */
class ImpossibleToGetFileContent extends BaseException
{
    public function __construct($url, $message)
    {
        parent::__construct("Impossible to get file content by {$url}: {$message}");
    }
}
