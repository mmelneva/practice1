<?php namespace Diol\FileclipExifTests\models;

use Diol\Fileclip\UploaderIntegrator;
use Diol\Fileclip\Version\BoxVersion;
use Diol\FileclipExif\Glue;
use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    use Glue;

    protected $fillable = ['image_file', 'image_remove', 'name', 'meta_title', 'header'];

    protected static function boot()
    {
        parent::boot();
        self::mountUploader(
            'image',
            UploaderIntegrator::getUploader('nodes/images', ['thumb' => new BoxVersion(50, 50)])
        );
    }
}
