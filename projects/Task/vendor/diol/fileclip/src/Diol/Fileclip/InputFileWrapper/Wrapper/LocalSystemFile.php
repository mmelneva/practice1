<?php
namespace Diol\Fileclip\InputFileWrapper\Wrapper;

use Diol\Fileclip\InputFileWrapper\IWrapper;

/**
 * Class LocalSystemFile
 * Wrapper for file in local system
 * @package Diol\Fileclip\InputFileWrapper\Wrapper
 */
class LocalSystemFile implements IWrapper
{
    /**
     * File in local system
     * @var string
     */
    private $file;

    /**
     * Create a wrapper
     * @param string $file - file in local system
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid()
    {
        return is_file($this->file);
    }

    /**
     * {@inheritDoc}
     */
    public function getExtension()
    {
        return (new \SplFileInfo($this->file))->getExtension();
    }

    public function getName($withExtension = true)
    {
        $fileInfo = new \SplFileInfo($this->file);

        $name = $fileInfo->getFilename();
        $extension = $fileInfo->getExtension();

        if (!$withExtension && $extension !== '' && !is_null($extension)) {
            $name = mb_substr($name, 0, mb_strlen($name) - mb_strlen(".{$extension}"));
        }

        return $name;
    }

    /**
     * {@inheritDoc}
     */
    public function save($dir, $fileName)
    {
        $newFilePath = $dir . '/' . $fileName;
        copy($this->file, $newFilePath);
    }
}
