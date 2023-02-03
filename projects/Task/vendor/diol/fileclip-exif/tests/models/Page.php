<?php namespace Diol\FileclipExifTests\models;

use Diol\Fileclip\UploaderIntegrator;
use Diol\Fileclip\Version\BoxVersion;
use Diol\FileclipExif\ExifDataWriter;
use Diol\FileclipExif\Glue;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use Glue;

    protected $fillable = ['image_file', 'image_remove', 'name'];

    protected static function boot()
    {
        parent::boot();

        self::mountUploader(
            'image',
            UploaderIntegrator::getUploader('pages/images', ['thumb' => new BoxVersion(50, 50)])
        );

        self::setExifDataCallback(
            function (self $page) {
                return [
                    ExifDataWriter::TAG_DESCRIPTION => $page->name . '!'
                ];
            }
        );
    }
}
