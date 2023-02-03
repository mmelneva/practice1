<?php
namespace Diol\Fileclip\support\models;

use Diol\Fileclip\Eloquent\Glue;
use Diol\Fileclip\UploaderIntegrator;
use Illuminate\Database\Eloquent\Model;

class DummyModel extends Model
{
    use Glue;

    protected $table = 'nodes';
    protected $fillable = ['foo_file', 'foo_remove', 'bar_file'];

    public static function boot()
    {
        parent::boot();
        static::mountUploader('foo', UploaderIntegrator::getUploader('files'));
        static::mountUploader('bar', UploaderIntegrator::getUploader('files'));
    }
}
