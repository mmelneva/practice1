<?php namespace App\Models;

use App\Models\Features\AttachedToNode;
use App\Models\Features\DeleteHelpers;
use Diol\Fileclip\Eloquent\Glue;
use Diol\Fileclip\UploaderIntegrator;
use Diol\Fileclip\Version\BoxVersion;
use Diol\FileclipExif\FileclipExif;

class ProductTypePage extends \Eloquent
{
    use AttachedToNode;
    use DeleteHelpers;
    use Glue;
    use FileclipExif;

    const WAY_MANUAL = 'manual';
    const WAY_FILTERED = 'filtered';

    protected $fillable = [
        'header',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'content',
        'content_bottom',
        'filter_query',
        'product_list_way',
        'manual_product_list_category_id',
        'default_filter_category_id',
        'in_popular',
        'order_icon_type',
        'use_reviews_associations',
        'popular_name',
        'short_content',
        'content_header',
        'content_header_show',
        'products_count',
        'sort_scheme',
        'use_sort_scheme',
        'parent_category_id',
        'type_order_button',
    ];

    /**
     * Value by default.
     *
     * @return string
     */
    public function getProductListWayAttribute()
    {
        $productListWay = array_get($this->attributes, 'product_list_way');
        if (is_null($productListWay)) {
            return self::WAY_MANUAL;
        } else {
            return $productListWay;
        }
    }

    /**
     * List of info about associated product type pages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productTypePageAssociations()
    {
        return $this->hasMany('App\Models\ProductTypePageAssociation', 'product_type_page_id');
    }

    /**
     * Association, which represents category for manual product list.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manualProductListCategory()
    {
        return $this->belongsTo('App\Models\CatalogCategory', 'manual_product_list_category_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(
            function (self $page) {
                self::deleteRelatedAll($page->productTypePageAssociations());

                // detact related reviews
                $page->reviews()->detach();
            }
        );
    }

    /**
     * Association with reviews
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function reviews()
    {
        return $this->belongsToMany(
                'App\Models\Reviews',
                'reviews_product_type_pages_associations',
                'product_type_pages_id',
                 'reviews_id'
        );

    }
}
