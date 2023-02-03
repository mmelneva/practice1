<?php
namespace Diol\Fileclip\Eloquent;

use Diol\Fileclip\Uploader\Uploader;
use Illuminate\Support\Facades\App;

/**
 * Trait Glue
 * Trait for Eloquent models to work with fileclip attachments
 * @package Diol\Fileclip\Eloquent
 */
trait Glue
{
    /**
     * List of uploaders mounted to field.
     * @var array
     */
    private static $uploaderList = [];

    /**
     * List of attachment handlers.
     * @var AttachmentHandlerList
     */
    private static $attachmentHandlerList;

    /**
     * Mount uploader to field
     * @param string $field
     * @param Uploader $uploader
     */
    public static function mountUploader($field, Uploader $uploader)
    {
        static::$uploaderList[$field] = $uploader;

        $handleAttachment = function (\Closure $handler) use ($field) {
            return function ($instance) use ($handler, $field) {
                $attachment = $instance->getAttachment($field);
                if (!is_null($attachment)) {
                    $handler($attachment);
                }
            };
        };

        static::saving(
            $handleAttachment(
                function (Attachment $attachment) {
                    $attachment->saving();
                }
            )
        );
        static::deleting(
            $handleAttachment(
                function (Attachment $attachment) {
                    $attachment->deleting();
                }
            )
        );

        $finished = $handleAttachment(
            function (Attachment $attachment) {
                $attachment->finished();
            }
        );
        static::saved($finished);
        static::deleted($finished);
    }


    /**
     * Add common attachment handler to list.
     *
     * @param AttachmentHandlerInterface $attachmentHandler
     */
    public static function addCommonAttachmentHandler(AttachmentHandlerInterface $attachmentHandler)
    {
        static::getCommonAttachmentHandlerList()->addAttachmentHandler($attachmentHandler);
    }

    /**
     * Get common attachment handler list.
     *
     * @return AttachmentHandlerList
     */
    public static function getCommonAttachmentHandlerList()
    {
        if (is_null(static::$attachmentHandlerList)) {
            static::$attachmentHandlerList = new AttachmentHandlerList();
        }

        return static::$attachmentHandlerList;
    }


    /**
     * Get list of fields which have attachment.
     *
     * @return array
     */
    public static function getAttachmentFields()
    {
        return array_keys(static::$uploaderList);
    }


    /**
     * Current attachments
     * @var Attachment[]
     */
    private $attachments = [];

    /**
     * Get attachment for field
     * @param string $field - field name
     * @return Attachment|null
     */
    public function getAttachment($field)
    {
        if (!isset($this->attachments[$field])) {
            if (isset(static::$uploaderList[$field])) {
                $attachment = new Attachment(
                    App::make('fileclip::wrapper_factory_collector'),
                    $this,
                    $field,
                    static::$uploaderList[$field],
                    new AttachmentQueueContainer(),
                    static::getCommonAttachmentHandlerList()
                );

                $this->attachments[$field] = $attachment;
            } else {
                return null;
            }
        }

        return $this->attachments[$field];
    }
}
