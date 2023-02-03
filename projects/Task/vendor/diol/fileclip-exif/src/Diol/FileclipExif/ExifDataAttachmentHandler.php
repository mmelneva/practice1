<?php namespace Diol\FileclipExif;

use Diol\Fileclip\Eloquent\Attachment;
use Illuminate\Support\Facades\Config;
use lsolesen\pel\PelException;

class ExifDataAttachmentHandler
{
    /**
     * @var bool
     */
    private $disabled = false;

    /**
     * @var ExifDataCallbackContainer
     */
    private $exifDataCallbackContainer;

    public function __construct(ExifDataCallbackContainer $exifDataCallbackContainer)
    {
        $this->exifDataCallbackContainer = $exifDataCallbackContainer;
    }

    /**
     * Handle the attachment.
     *
     * @param Attachment $attachment
     * @return mixed
     */
    public function handle(Attachment $attachment)
    {
        if ($this->disabled) {
            return;
        }

        $model = $attachment->getModelInstance();

        $dataToWrite = $this->exifDataCallbackContainer->call($model);

        if (!isset($dataToWrite[ExifDataWriter::TAG_DESCRIPTION])) {
            $dataToWrite[ExifDataWriter::TAG_DESCRIPTION] =
                !empty($model->header) ? $model->header : $model->name;
        }

        if (!isset($dataToWrite[ExifDataWriter::TAG_COMMENT])) {
            $dataToWrite[ExifDataWriter::TAG_COMMENT] =
                !empty($model->meta_title) ? $model->meta_title : $model->name;
        }

        if (!isset($dataToWrite[ExifDataWriter::TAG_COPYRIGHT])) {
            $copyright = Config::get('fileclip-exif::copyright', null);
            if (!is_scalar($copyright)) {
                $copyright = '';
            }
            $dataToWrite[ExifDataWriter::TAG_COPYRIGHT] = $copyright;
        }


        $exifDataWriter = new ExifDataWriter($dataToWrite);
        foreach ($attachment->getAvailableVersions() as $version) {
            try {
                $exifDataWriter->writeTo($attachment->getAbsolutePath($version));
            } catch (PelException $e) {
                // do nothing
            }
        }
    }

    /**
     * Disable or enable handling.
     * @param $value
     */
    public function setDisabled($value)
    {
        $this->disabled = $value;
    }
}
