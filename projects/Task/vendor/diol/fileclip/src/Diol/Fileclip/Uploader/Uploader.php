<?php

namespace Diol\Fileclip\Uploader;

use Diol\Fileclip\InputFileWrapper\IWrapper;
use Diol\Fileclip\InputFileWrapper\WrapperTool;
use Diol\Fileclip\Uploader\NameGenerator\INameGenerator;
use Diol\Fileclip\Version\DefaultVersion;
use Diol\Fileclip\Version\IVersion;
use Illuminate\Filesystem\Filesystem;
use Imagine\Image\ImagineInterface;

/**
 * Class Uploader
 * Uploader class (for handle uploading, retrieving and removing files, images and their versions)
 * @package Diol\Fileclip\Uploader
 */
class Uploader
{
    /**
     * Absolute path to public root
     * @var string
     */
    private $publicRoot;

    /**
     * Absolute path to storage root
     * @var string
     */
    private $storageRoot;

    /**
     * @var \Imagine\Image\ImagineInterface
     */
    private $imagine;

    /**
     * @var INameGenerator
     */
    private $nameGenerator;

    /**
     * Relative path to directory in storage
     * @var string
     */
    private $path;

    /**
     * List of version handlers
     * @var IVersion[]
     */
    private $versionList;

    /**
     * Default version name
     * @var string
     */
    private $defaultVersion = 'default';

    public function __construct(
        $publicRoot,
        $storageRoot,
        INameGenerator $nameGenerator,
        $path,
        ImagineInterface $imagine,
        array $versionList = []
    ) {
        $this->publicRoot = $publicRoot;
        $this->storageRoot = $storageRoot;
        $this->imagine = $imagine;
        $this->nameGenerator = $nameGenerator;
        $this->path = $path;

        foreach ($versionList as $v) {
            if ($v !== null && $v instanceof IVersion === false) {
                throw new IncorrectVersion;
            }
        }
        if (!array_key_exists($this->defaultVersion, $versionList)) {
            $versionList[$this->defaultVersion] = new DefaultVersion();
        }
        $this->versionList = $versionList;
    }

    /**
     * Store file from file wrapper
     * @param $fileToStore
     * @return Stored|null
     */
    public function store(IWrapper $fileToStore)
    {
        if ($fileToStore->isValid()) {
            $generatedFileName = $this->nameGenerator->generateName($this, $fileToStore);

            $fs = new Filesystem();

            $fs->makeDirectory($this->getAbsoluteStoragePath(), 0777, true, true);
            $fs->makeDirectory($this->getAbsolutePath(), 0777, true, true);

            $fileToStore->save($this->getAbsoluteStoragePath(), $generatedFileName);

            return new Stored($this, $generatedFileName);
        } else {
            return null;
        }
    }

    /**
     * Retrieve stored file
     * @param $fileName
     * @return Stored|null
     */
    public function retrieve($fileName)
    {
        if (is_file($this->getAbsoluteStoragePath($fileName))) {
            return new Stored($this, $fileName);
        } elseif (is_file($this->getAbsolutePath($fileName))) {
            return new Stored($this, $fileName);
        } else {
            return null;
        }
    }

    /**
     * Get relative path to dir or file
     * @param string|null $fileName
     * @return string
     */
    public function getRelativePath($fileName = null)
    {
        $path = '/' . trim($this->path, '/');
        if (!is_null($fileName)) {
            $path .= '/' . $fileName;
        }
        return $path;
    }

    /**
     * Get absolute path to dir or file
     * @param string|null $fileName
     * @return string
     */
    public function getAbsolutePath($fileName = null)
    {
        return rtrim($this->publicRoot, '/') . $this->getRelativePath($fileName);
    }

    /**
     * Get absolute path to original dir or file
     * @param string|null $fileName
     * @return string
     */
    public function getAbsoluteStoragePath($fileName = null)
    {
        return rtrim($this->storageRoot, '/') . $this->getRelativePath($fileName);
    }

    /**
     * Get current imagine object
     * @return ImagineInterface
     */
    public function getImagine()
    {
        return $this->imagine;
    }

    /**
     * Get current name generator
     * @return INameGenerator
     */
    public function getNameGenerator()
    {
        return $this->nameGenerator;
    }

    /**
     * Get current version handlers
     * @return \Diol\Fileclip\Version\IVersion[]
     */
    public function getVersionHandlers()
    {
        return $this->versionList;
    }

    /**
     * Get version handler by its id
     * @param $versionHandlerId
     * @return \Diol\Fileclip\Version\IVersion|null
     */
    public function getVersionHandler($versionHandlerId)
    {
        if (isset($this->versionList[$versionHandlerId])) {
            return $this->versionList[$versionHandlerId];
        } else {
            return null;
        }
    }

    public function getAvailableVersions()
    {
        return array_keys($this->versionList);
    }

    public function getDefaultVersionKey()
    {
        return $this->defaultVersion;
    }
}
