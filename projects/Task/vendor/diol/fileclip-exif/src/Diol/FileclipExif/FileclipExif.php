<?php namespace Diol\FileclipExif;

trait FileclipExif
{
    /**
     * @var ExifDataCallbackContainer
     */
    protected static $callbackExifContainer;

    /**
     * @var ExifDataAttachmentHandler
     */
    protected static $exifDataAttachmentHandler;

    public static function bootFileclipExif()
    {
        static::addCommonAttachmentHandler(
            new ExifAttachmentHandler('versionsUpdated', static::getExifDataAttachmentHandler())
        );
        static::addCommonAttachmentHandler(
            new ExifAttachmentHandler('finished', static::getExifDataAttachmentHandler())
        );
    }


    /**
     * Set exif data callback.
     *
     * @param callable $callback
     */
    protected static function setExifDataCallback(callable $callback)
    {
        static::getExifCallbackContainer()->setCallback($callback);
    }


    /**
     * @return ExifDataCallbackContainer
     */
    protected static function getExifCallbackContainer()
    {
        if (is_null(static::$callbackExifContainer)) {
            static::$callbackExifContainer = new ExifDataCallbackContainer();
        }

        return static::$callbackExifContainer;
    }

    /**
     * @return ExifDataAttachmentHandler
     */
    protected static function getExifDataAttachmentHandler()
    {
        if (is_null(static::$exifDataAttachmentHandler)) {
            static::$exifDataAttachmentHandler = new ExifDataAttachmentHandler(static::getExifCallbackContainer());
        }

        return static::$exifDataAttachmentHandler;
    }

    /**
     * Perform callable without exif data updating.
     *
     * @param callable $callable
     * @return mixed
     */
    protected static function withoutUpdatingExifData(callable $callable)
    {
        $handler = static::getExifDataAttachmentHandler();

        $handler->setDisabled(true);
        $result = $callable();
        $handler->setDisabled(false);

        return $result;
    }


    /**
     * Update exif data for all the attachments.
     */
    public function updateExifData()
    {
        foreach (static::getAttachmentFields() as $attachmentField) {
            $attachment = $this->getAttachment($attachmentField);
            static::getExifDataAttachmentHandler()->handle($attachment);
        }
    }
}
