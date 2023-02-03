<?php namespace Diol\Fileclip\Eloquent;

/**
 * Interface AttachmentHandlerInterface
 * Handler for attachment.
 * Allow to add different handlers on attachment events.
 *
 * @package Diol\Fileclip\Eloquent
 */
interface AttachmentHandlerInterface
{
    /**
     * Get event name.
     *
     * @return string
     */
    public function getEvent();

    /**
     * Handle the attachment.
     *
     * @param Attachment $attachment
     * @return mixed
     */
    public function handle(Attachment $attachment);
}
