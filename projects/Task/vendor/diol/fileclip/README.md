# Laraclip

File attachment for Eloquent models in Laravel 4



## Basic usage

First of all you need to include service provider:

    'Diol\Fileclip\FileclipServiceProvider'

For usage you need to use trait **Diol\Fileclip\Eloquent\Glue**, after this in static method **boot** of model
you could mount uploaders. Don't forget to call parent boot method.

Example:

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



## Uploader usage

You can also use uploader without Eloquent.

Method **UploaderIntegrator::getUploader()** encapsulates creating object of the class **Diol\Fileclip\Uploader\Uploader**.

You also can create it manually:

    new Uploader($storageRoot, $nameGenerator, $path, $imagine, $versionList)


### Storage Root

**$storageRoot** is path to storage root - public path by default (if you use **UploaderIntegrator::getUploader()**).

You can access or change it value by config key **fileclip::storage_root**:

    Config::get('fileclip::storage_root')


### Name generator

**$nameGenerator** is object to generate new names.

Should implement interface **Diol\Fileclip\Uploader\NameGenerator\INameGenerator**.
By default it is random name generator.

You can change default value or access existing with DI container key **fileclip::name_generator**:

    App::make('fileclip::name_generator')


### Path

**$path** is relative to *$storageRoot* path to storage dir. No default value.


### Imagine

**$imagine** is imagine object to work with images.

Should implement interface **Imagine\Image\ImagineInterface**

You can change default value or access existing with DI container key **fileclip::imagine**:

    App::make('fileclip::imagine')


### Version list

**$versionList** is array of version handlers to handle images.

Each version should implement interface **Diol\Fileclip\Version\IVersion**



## Input files

Uploader can store files, which are wrapped by class, implementing interface **Diol\Fileclip\InputFileWrapper\IWrapper**

By default there are next wrappers:

 * **Diol\Fileclip\InputFileWrapper\Wrapper\LocalSystemFile** - for files in local system. After saving they won't be deleted.
 * **Diol\Fileclip\InputFileWrapper\Wrapper\SymfonyUploadedFile** - files, uploaded via Symfony Uploader. After saving they will be deleted.
 * **Diol\Fileclip\InputFileWrapper\Wrapper\NullWrapper** - dummy wrapper. No file.


Wrappers except **Diol\Fileclip\InputFileWrapper\Wrapper\NullWrapper** have factories, which checks if input is valid and creates appropriate wrapper.
Each factory should implement interface **Diol\Fileclip\InputFileWrapper\IWrapperFactory**.

Factories are collected in **Diol\Fileclip\InputFileWrapper\WrapperFactoryCollector**, which is available in DI container by key 'fileclip::wrapper_factory_collector':

    App::make('fileclip::wrapper_factory_collector')

You could add your own wrapper factories or completely replace them.


# Contributing

## Testing

For testing you need to start built in PHP-server (for testing file uploading via HTTP):

    php -S localhost:3000

