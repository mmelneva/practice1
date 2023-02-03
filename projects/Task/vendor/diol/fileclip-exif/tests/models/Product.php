<?php namespace Diol\FileclipExifTests\models;

use Diol\Fileclip\UploaderIntegrator;
use Diol\FileclipExif\ExifDataWriter;
use Diol\FileclipExif\Glue;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Glue;

    protected $fillable = ['image_file', 'image_remove', 'name'];

    protected static function boot()
    {
        parent::boot();

        self::mountUploader(
            'image',
            UploaderIntegrator::getUploader('pages/images')
        );

        self::setExifDataCallback(
            function () {
                return [
                    ExifDataWriter::TAG_COPYRIGHT => 'my copyright'
                ];
            }
        );
    }
}
