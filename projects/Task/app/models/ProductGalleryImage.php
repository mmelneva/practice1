<?php namespace App\Models;

use App\Models\Features\AutoPublish;
use App\Models\Features\SubProductExif;
use Diol\Fileclip\Eloquent\Glue;
use Diol\Fileclip\UploaderIntegrator;
use Diol\Fileclip\Version\BoxVersion;

/**
 * App\Models\ProductGalleryImage
 *
 * @property integer $id 
 * @property integer $catalog_product_id 
 * @property string $name 
 * @property boolean $publish 
 * @property integer $position 
 * @property string $image 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \App\Models\CatalogProduct $product 
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductGalleryImage whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductGalleryImage whereCatalogProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductGalleryImage whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductGalleryImage wherePublish($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductGalleryImage wherePosition($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductGalleryImage whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductGalleryImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ProductGalleryImage whereUpdatedAt($value)
 */
class ProductGalleryImage extends \Eloquent
{
    use Glue;
    use SubProductExif;
    use AutoPublish;

    protected $fillable = [
        'catalog_product_id',
        'name',
        'publish',
        'position',
        'image_file',
        'image_remove'
    ];

    /**
     * Belongs to product relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo('App\Models\CatalogProduct', 'catalog_product_id');
    }

    public static function boot()
    {
        parent::boot();

        static::mountUploader(
            'image',
            UploaderIntegrator::getWatermarkedUploader(
                'uploads/product_gallery_images',
                [
                    'thumb' => new BoxVersion(50, 50),
                    'big' => new BoxVersion(400, 9999),
                    'small' => new BoxVersion(90, 9999)
                ]
            )
        );
    }
}
