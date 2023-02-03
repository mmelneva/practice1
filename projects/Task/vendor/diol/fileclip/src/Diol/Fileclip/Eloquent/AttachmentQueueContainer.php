<?php
namespace Diol\Fileclip\Eloquent;

use Diol\Fileclip\Uploader\Stored;

/**
 * Class AttachmentQueueContainer
 * Class-container for attachment files for model.
 * Collects queue of files to delete and to create versions
 * @package Diol\Fileclip\Eloquent
 */
class AttachmentQueueContainer
{
    /**
     * Files to delete
     * @var array
     */
    private $deleteQueue = [];

    /**
     * Stored files to create versions
     * @var Stored[]
     */
    private $versionQueue = [];

    /**
     * Add file to delete queue
     * @param $value
     */
    public function addToDeleteQueue($value)
    {
        $this->deleteQueue[] = $value;
    }

    /**
     * Get files to delete
     * @return array
     */
    public function getDeleteQueue()
    {
        return $this->deleteQueue;
    }

    /**
     * Clear queue files to delete
     */
    public function clearDeleteQueue()
    {
        $this->deleteQueue = [];
    }

    /**
     * Add stored file to list to create versions
     * @param Stored $stored
     */
    public function addToVersionQueue(Stored $stored)
    {
        $this->versionQueue[] = $stored;
    }

    /**
     * Get stored files to create versions
     * @return \Diol\Fileclip\Uploader\Stored[]
     */
    public function getVersionQueue()
    {
        return $this->versionQueue;
    }

    /**
     * Clear stored files to create versions
     */
    public function clearVersionQueue()
    {
        $this->versionQueue = [];
    }
}
