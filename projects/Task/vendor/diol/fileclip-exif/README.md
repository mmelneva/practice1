# Fileclip-exif

**Fileclip-exif** is an extension for **fileclip**.
It allowes you to add EXIF information to JPEG-attachments.


## Usage

Include service provider:

    'Diol\FileclipExif\FileclipExifServiceProvider'

Use it in your model, which is already using fileclip:

    class CatalogProduct extends \Eloquent
    {
        use Glue;
        use FileclipExif;

        ...
    }

It will set copyright, description and comment to images.

By default:
* description is header or name of model
* comment is title or name of model
* copyright is a constant for all the project

## Customize

To customize global copyright publish and change config:

    php artisan config:publish diol/fileclip-exif

To customize EXIF data for model define in boot method the next code:


    public static function boot()
    {
        parent::boot();

        static::setExifDataCallback(
            function ($model) {
                return [
                    ExifDataWriter::TAG_COPYRIGHT => 1,
                    ExifDataWriter::TAG_DESCRIPTION => 2,
                    ExifDataWriter::TAG_COMMENT => 3,
                ];
            }
        );
    }
