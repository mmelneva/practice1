<?php namespace Diol\Fileclip\Eloquent;

class AttachmentHandlerList
{
    /**
     * @var AttachmentHandlerInterface[]
     */
    private $attachmentHandlerList = [];


    /**
     * Add attachment handler.
     *
     * @param AttachmentHandlerInterface $attachmentHandler
     */
    public function addAttachmentHandler(AttachmentHandlerInterface $attachmentHandler)
    {
        $this->attachmentHandlerList[] = $attachmentHandler;
    }


    /**
     * Handle attachment event.
     *
     * @param Attachment $attachment
     * @param $event
     */
    public function handle(Attachment $attachment, $event)
    {
        foreach ($this->attachmentHandlerList as $handler) {
            if ($handler->getEvent() === $event) {
                $handler->handle($attachment);
            }
        }
    }
}
