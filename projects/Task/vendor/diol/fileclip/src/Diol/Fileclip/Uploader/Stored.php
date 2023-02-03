<?php

namespace Diol\Fileclip\Uploader;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use Imagine\Exception\Exception as ImagineException;

/**
 * Class Stored
 * Class which represents stored file with its versions
 * @package Diol\Fileclip\Uploader
 */
class Stored
{
    /**
     * @var Uploader
     */
    private $uploader;

    /**
     * Current file name
     * @var string
     */
    private $fileName;

    /**
     * Default version name
     * @var string
     */
    private $defaultVersion;

    /**
     * Create stored file object
     * @param Uploader $uploader
     * @param string $fileName
     */
    public function __construct(Uploader $uploader, $fileName)
    {
        $this->uploader = $uploader;
        $this->fileName = $fileName;
        $this->defaultVersion = $uploader->getDefaultVersionKey();
    }

    /**
     * Get current file name
     * @return string
     */
    public function getName()
    {
        return $this->fileName;
    }

    /**
     * Remove current file with all versions
     */
    public function remove()
    {
        // todo: test this
        $this->removeVersions();

        $file = $this->getAbsolutePath();
        if (is_file($file)) {
            unlink($this->getAbsolutePath());
        }

        $file = $this->uploader->getAbsoluteStoragePath($this->fileName);
        if (is_file($file)) {
            unlink($this->uploader->getAbsoluteStoragePath($this->fileName));
        }
    }

    /**
     * Remove all versions
     */
    public function removeVersions()
    {
        // todo: test this
        foreach ($this->uploader->getVersionHandlers() as $key => $versionHandler) {
            $file = $this->getAbsolutePath($key);
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Create all versions
     */
    public function createVersions()
    {
        try {
            foreach ($this->uploader->getVersionHandlers() as $key => $versionHandler) {
                $filePath = $this->uploader->getAbsoluteStoragePath($this->fileName);
                $fileFormat = normalize_format(pathinfo($filePath, \PATHINFO_EXTENSION));

                $openedImage = $this->uploader->getImagine()->open($filePath);
                $options = $versionHandler->getOptions();

                if (!isset($options['quality'])) {
                    switch ($fileFormat) {
                        case 'png':
                            $options['quality'] = 0;
                            break;

                        case 'jpeg':
                            $options['quality'] = 85;
                            break;
                    }
                }

                $versionPath = $this->getAbsolutePath($key);
                $versionDirectoryPath = dirname($versionPath);

                $fs = new Filesystem();
                $fs->makeDirectory($versionDirectoryPath, 0777, true, true);

                $versionHandler->modify($openedImage, $this->uploader->getImagine())->save($versionPath, $options);
            }
        } catch (ImagineException $e) {
            if (isset($filePath, $fileFormat)) {
                if (in_array($fileFormat, ['png', 'jpeg'])) {
                    if ($e instanceof \Exception) {
                        echo PHP_EOL . "Warning! Exception while creating new versions for image: {$e->getMessage()}"
                            . PHP_EOL;
                    }
                    Log::warning($e);
                }
            }
            // do nothing, it's, probably, not image and we don't need version for it
            copy($this->getAbsoluteStoragePath(), $this->getAbsolutePath());
        }
    }

    /**
     * Get relative path to original file or versions
     * @param null|string $version - version name
     * @return string
     */
    public function getRelativePath($version = null)
    {
        // todo: test this
        $fileName = $this->fileName;
        if (!is_null($version) && $version != $this->defaultVersion) {
            $fileName = $version . '_' . $fileName;
        }

        return $this->uploader->getRelativePath($fileName);
    }

    /**
     * Get absolute file to original path or version
     * @param null|string $version - version name
     * @return string
     */
    public function getAbsolutePath($version = null)
    {
        // todo: test this
        $fileName = $this->fileName;
        if (!is_null($version) && $version != $this->defaultVersion) {
            $fileName = $version . '_' . $fileName;
        }

        return $this->uploader->getAbsolutePath($fileName);
    }

    /**
     * Get absolute path to original dir or file.
     *
     * @return string
     */
    public function getAbsoluteStoragePath()
    {
        // todo: test this
        return $this->uploader->getAbsoluteStoragePath($this->fileName);
    }
}
