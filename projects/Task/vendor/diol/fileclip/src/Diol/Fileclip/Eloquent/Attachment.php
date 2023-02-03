<?php
namespace Diol\Fileclip\Eloquent;

use Diol\Fileclip\InputFileWrapper\WrapperFactoryCollector;
use Diol\Fileclip\Uploader\Uploader;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Attachment
 * Class which represents attachment for exact field in exact object
 * @package Diol\Fileclip\Eloquent
 */
class Attachment
{
    /**
     * Model instance
     * @var \Illuminate\Database\Eloquent\Model
     */
    private $instance;

    /**
     * Model field for attachment
     * @var string
     */
    private $field;

    /**
     * Uploader to manage attachment
     * @var \Diol\Fileclip\Uploader\Uploader
     */
    private $uploader;

    /**
     * Container to queue file to delete or store
     * @var AttachmentQueueContainer
     */
    private $queueContainer;

    /**
     * Factory to create file wrappers
     * @var WrapperFactoryCollector
     */
    private $fileWrapperFactory;

    /**
     * Attachment handler list.
     * @var AttachmentHandlerList
     */
    private $attachmentHandlerList;


    /**
     * Create attachment for field of model
     * @param WrapperFactoryCollector $fileWrapperFactory - file wrapper factory for input
     * @param Model $instance - model instance
     * @param string $field - model field for attachment
     * @param Uploader $uploader - uploader to manage attachment
     * @param AttachmentQueueContainer $queueContainer - container to queue some files
     * @param AttachmentHandlerList $attachmentHandlerList - list of attachment handlers
     */
    public function __construct(
        WrapperFactoryCollector $fileWrapperFactory,
        Model $instance,
        $field,
        Uploader $uploader,
        AttachmentQueueContainer $queueContainer,
        AttachmentHandlerList $attachmentHandlerList
    ) {
        $this->fileWrapperFactory = $fileWrapperFactory;
        $this->instance = $instance;
        $this->field = $field;
        $this->uploader = $uploader;
        $this->queueContainer = $queueContainer;
        $this->attachmentHandlerList = $attachmentHandlerList;
    }

    /**
     * Get model for this attachment.
     *
     * @return Model
     */
    public function getModelInstance()
    {
        return $this->instance;
    }

    /**
     * Get field in model for this attachment.
     *
     * @return string
     */
    public function getModelField()
    {
        return $this->field;
    }

    /**
     * Get uploader for this attachment.
     *
     * @return Uploader
     */
    public function getUploader()
    {
        return $this->uploader;
    }

    /**
     * Trigger handlers for certain event.
     * @param $event
     */
    private function triggerEvent($event)
    {
        $this->attachmentHandlerList->handle($this, $event);
    }

    /**
     * Prepare attachment on saving
     */
    public function saving()
    {
        $removeKey = $this->field . '_remove';
        $newFileKey = $this->field . '_file';

        $needToRemove = isset($this->instance[$removeKey]);
        $needToStoreNewFile = isset($this->instance[$newFileKey]);

        if ($needToRemove) {
            $this->instance[$this->field] = null;
            $this->prepareToDeleteExisting();
        } elseif ($needToStoreNewFile) {
            $fileWrapper = $this->fileWrapperFactory->getWrapper($this->instance[$newFileKey]);
            $stored = $this->uploader->store($fileWrapper);
            if (!is_null($stored)) {
                $this->instance[$this->field] = $stored->getName();
                $this->prepareToDeleteExisting();
                $this->queueContainer->addToVersionQueue($stored);
            }
        }

        unset($this->instance[$removeKey]);
        unset($this->instance[$newFileKey]);
    }

    /**
     * Prepare attachment on deleting
     */
    public function deleting()
    {
        $this->prepareToDeleteExisting(true);
    }

    /**
     * Prepare actions on finish
     */
    public function finished()
    {
        foreach ($this->queueContainer->getDeleteQueue() as $toDelete) {
            $stored = $this->uploader->retrieve($toDelete);
            if (!is_null($stored)) {
                $stored->remove();
            }
        }
        $this->queueContainer->clearDeleteQueue();

        foreach ($this->queueContainer->getVersionQueue() as $toCreateVersion) {
            $toCreateVersion->createVersions();
        }
        $this->queueContainer->clearVersionQueue();

        $this->triggerEvent('finished');
    }

    /**
     * Prepare existing file to delete
     *
     * @param $forceDelete - force deleting if it's possible.
     * Otherwise file will not be deleted if it's old name matches current one.
     */
    private function prepareToDeleteExisting($forceDelete = false)
    {
        $originalFile = $this->instance->getOriginal($this->field);
        $currentFile = $this->instance->getAttribute($this->field);
        if (!is_null($originalFile) && ($forceDelete || $originalFile != $currentFile)) {
            $this->queueContainer->addToDeleteQueue($originalFile);
        }
    }


    /**
     * Get absolute storage path (in file system) to original stored file.
     * @return null|string
     */
    public function getAbsoluteStoragePath()
    {
        $stored = $this->retrieveCurrent();
        if (!is_null($stored)) {
            if (file_exists($path = $stored->getAbsoluteStoragePath())) {
                return $path;
            }
        }

        return null;
    }


    /**
     * Get absolute path (in file system) to stored file
     * @param null|string $version
     * @return null|string
     */
    public function getAbsolutePath($version = null)
    {
        $stored = $this->retrieveCurrent();
        if (!is_null($stored)) {
            if (file_exists($path = $stored->getAbsolutePath($version))) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Get relative path(according to relative dir) to stored file
     * @param null|string $version
     * @return null|string
     */
    public function getRelativePath($version = null)
    {
        $stored = $this->retrieveCurrent();
        if (!is_null($stored)) {
            if (file_exists($this->getAbsolutePath($version))) {
                return $stored->getRelativePath($version);
            }
        }
        return null;
    }

    /**
     * Get remote path to stored file
     * @param null $version
     * @param bool|true $absolute
     * @return null|string
     */
    public function getRemotePath($version = null, $absolute = true)
    {
        $remotePath = $this->getRelativePath($version);

        if (is_null($remotePath)) {
            return null;
        }

        if ($absolute) {
            return \URL::to($remotePath);
        }

        return $remotePath;
    }

    /**
     * Get current stored file
     * @return \Diol\Fileclip\Uploader\Stored|null
     */
    public function retrieveCurrent()
    {
        return $this->uploader->retrieve($this->instance->getAttribute($this->field));
    }

    /**
     * File version exists?
     *
     * @param null|string $version
     * @return bool
     */
    public function exists($version = null)
    {
        return is_file($this->getAbsolutePath($version));
    }

    /**
     * Update all versions
     */
    public function updateVersions()
    {
        $stored = $this->retrieveCurrent();
        if ($stored !== null) {
            if (is_file($stored->getAbsoluteStoragePath())) {
                // Remove old versions.
                $stored->removeVersions();
                // Create new versions.
                $stored->createVersions();
            }
        }

        $this->triggerEvent('versionsUpdated');
    }

    /**
     * Get list of available versions.
     *
     * @return array
     */
    public function getAvailableVersions()
    {
        return $this->uploader->getAvailableVersions();
    }

    /**
     * Get list of existing versions.
     *
     * @return array
     */
    public function getExistingVersions()
    {
        return array_filter(
            $this->uploader->getAvailableVersions(),
            function ($version) {
                return $this->exists($version);
            }
        );
    }
}
