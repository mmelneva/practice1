<?php namespace App\Models;

use App\Models\Features\AutoPublish;
use App\Models\Features\DeleteHelpers;
use Diol\Fileclip\Eloquent\Glue;
use Diol\Fileclip\UploaderIntegrator;
use Diol\Fileclip\Version\BoxVersion;
use Diol\FileclipExif\FileclipExif;

/**
 * App\Models\CatalogProduct
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $category_id
 * @property string $name
 * @property boolean $publish
 * @property integer $position
 * @property string $image
 * @property float $price
 * @property string $header
 * @property string $content
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property-read \App\Models\CatalogCategory $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Attribute[] $additionalAttributes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AttributeValue[] $attributeValues
 * @property-read mixed $enabled
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogProduct whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogProduct whereCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogProduct whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogProduct wherePublish($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogProduct wherePosition($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogProduct whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogProduct wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogProduct whereHeader($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogProduct whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogProduct whereMetaTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogProduct whereMetaKeywords($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogProduct whereMetaDescription($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductTypePageAssociation[] $productTypePageAssociations
 */
class CatalogProduct extends \Eloquent
{
    use Glue;
    use FileclipExif;
    use DeleteHelpers;
    use AutoPublish;

    protected $fillable = [
        'category_id',
        'name',
        'position',
        'publish',
        'header',
        'content',
        'small_content',
        'price',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'image_file',
        'image_remove',
        'preview_image_file',
        'preview_image_remove',
        'built_in',
        'no_template_text',
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\CatalogCategory');
    }

    /** Association with categories
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function associatedCategories()
    {
        return $this->belongsToMany(
            'App\Models\CatalogCategory',
            'product_category_associations',
            'product_id',
            'category_id'
        );
    }

    /**
     * Product has many additional attributes
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function additionalAttributes()
    {
        return $this->belongsToMany(
            'App\Models\Attribute',
            'attribute_values',
            'product_id',
            'attribute_id'
        );
    }

    public function attributeValues()
    {
        return $this->hasMany('App\Models\AttributeValue', 'product_id');
    }

    /**
     * List of info about associated product type pages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productTypePageAssociations()
    {
        return $this->hasMany('App\Models\ProductTypePageAssociation', 'catalog_product_id');
    }

    /**
     * List of info about associated home pages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function homePageAssociations()
    {
        return $this->hasMany('App\Models\ProductHomepageAssociation', 'catalog_product_id');
    }

    /**
     * Products relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function galleryImages()
    {
        return $this->hasMany('App\Models\ProductGalleryImage', 'catalog_product_id');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Reviews', 'product_id');
    }


    protected static function boot()
    {
        parent::boot();

        self::mountUploader(
            'image',
            UploaderIntegrator::getWatermarkedUploader(
                "uploads/catalog_products/image",
                [
                    'default' => new BoxVersion(1000, 1000),
                    'thumb' => new BoxVersion(50, 50),
                    'middle' => new BoxVersion(240, 9999),
                    'big' => new BoxVersion(400, 9999),
                    'small' => new BoxVersion(90, 9999)
                ]
            )
        );

        self::mountUploader(
            'preview_image',
            UploaderIntegrator::getWatermarkedUploader(
                "uploads/catalog_products/preview_image",
                [
                    'thumb' => new BoxVersion(50, 50),
                    'middle' => new BoxVersion(240, 9999),
                ]
            )
        );

        static::deleting(
            function (self $model) {
                self::deleteRelatedAll($model->productTypePageAssociations());
                self::deleteRelatedAll($model->homePageAssociations());

                // delete all attribute values for this product
                self::deleteRelatedAll($model->attributeValues());

                // delete all galleryImages for this product
                self::deleteRelatedAll($model->galleryImages());

                $model->associatedCategories()->detach();

                // detach orders with this modification
                Order::where('product_id', $model->id)->update(['product_id' => null]);

                self::deleteRelatedAll($model->reviews());
            }
        );
    }
}
