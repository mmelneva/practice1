<?php
namespace Diol\Fileclip\InputFileWrapper\Wrapper;

use Diol\Fileclip\InputFileWrapper\IWrapper;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class SymfonyUploadedFile
 * Wrapper to uploaded file through symfony request
 * @package Diol\Fileclip\InputFileWrapper\Wrapper
 */
class SymfonyUploadedFile implements IWrapper
{
    /**
     * Uploaded file
     * @var UploadedFile
     */
    private $uploadedFile;

    /**
     * Create a wrapper
     * @param UploadedFile $uploadedFile - uploaded file
     */
    public function __construct(UploadedFile $uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid()
    {
        return $this->uploadedFile->isValid();
    }

    /**
     * {@inheritDoc}
     */
    public function getExtension()
    {
        return $this->uploadedFile->getClientOriginalExtension();
    }

    /**
     * {@inheritDoc}
     */
    public function getName($withExtension = true)
    {
        $name = $this->uploadedFile->getClientOriginalName();
        $extension = $this->uploadedFile->getClientOriginalExtension();

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
        $this->uploadedFile->move($dir, $fileName);
    }
}
