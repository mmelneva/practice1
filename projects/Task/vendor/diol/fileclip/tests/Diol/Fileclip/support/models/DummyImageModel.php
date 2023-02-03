<?php
namespace Diol\Fileclip\support\models;

use Diol\Fileclip\Eloquent\Glue;
use Diol\Fileclip\UploaderIntegrator;
use Diol\Fileclip\Version\BoxVersion;
use Illuminate\Database\Eloquent\Model;

class DummyImageModel extends Model
{
    use Glue;

    protected $table = 'images';
    protected $fillable = ['image_file', 'image_remove'];

    public static function boot()
    {
        parent::boot();
        static::mountUploader(
            'image',
            UploaderIntegrator::getUploader('images', ['thumb' => new BoxVersion(50, 50)])
        );
    }
}
