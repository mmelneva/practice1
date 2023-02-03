<?php namespace Diol\FileclipExif;

use Diol\Fileclip\Eloquent\Attachment;
use Diol\Fileclip\Eloquent\AttachmentHandlerInterface;

/**
 * Class ExifAttachmentHandler
 * @package Diol\FileclipExif
 */
class ExifAttachmentHandler implements AttachmentHandlerInterface
{
    /**
     * @var string
     */
    private $event;

    /**
     * @var ExifDataAttachmentHandler
     */
    private $exifDataAttachmentHandler;

    public function __construct($event, ExifDataAttachmentHandler $exifDataAttachmentHandler)
    {
        $this->event = $event;
        $this->exifDataAttachmentHandler = $exifDataAttachmentHandler;
    }

    /**
     * Get event name.
     *
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Handle the attachment.
     *
     * @param Attachment $attachment
     * @return mixed
     */
    public function handle(Attachment $attachment)
    {
        $this->exifDataAttachmentHandler->handle($attachment);
    }
}
