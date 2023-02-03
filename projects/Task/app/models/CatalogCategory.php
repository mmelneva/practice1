<?php namespace App\Models;

use App\Models\Features\AutoPublish;
use App\Models\Features\DeleteHelpers;
use Diol\Fileclip\Eloquent\Glue;
use Diol\Fileclip\UploaderIntegrator;
use Diol\Fileclip\Version\BoxVersion;
use Diol\FileclipExif\FileclipExif;

/**
 * \App\Models\CatalogCategory
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $parent_id
 * @property string $name
 * @property boolean $publish
 * @property integer $position
 * @property string $alias
 * @property string $header
 * @property string $content
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property string $content_bottom
 * @property string $content_for_sidebar
 * @property string $logo
 * @property boolean $top_menu
 * @property string $logo_active
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CatalogProduct[] $products
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductTypePage[] $manualProductTypePages
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogCategory whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogCategory whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogCategory whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogCategory wherePublish($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogCategory wherePosition($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogCategory whereAlias($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogCategory whereHeader($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogCategory whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogCategory whereMetaTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogCategory whereMetaKeywords($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogCategory whereMetaDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogCategory whereContentBottom($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogCategory whereLogo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogCategory whereTopMenu($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\CatalogCategory whereLogoActive($value)
 */
class CatalogCategory extends \Eloquent
{
    use DeleteHelpers;
    use Glue;
    use FileclipExif;
    use AutoPublish;

    protected $fillable = [
        'parent_id',
        'name',
        'publish',
        'position',
        'alias',
        'header',
        'content',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'top_menu',
        'content_bottom',
        'logo_file',
        'logo_remove',
        'logo_active_file',
        'logo_active_remove',
        'similar_products_block_name',
        'order_icon_type',
        'use_reviews_associations',
        'content_for_submenu',
        'content_for_sidebar',
        'type_order_button',
    ];


    public function parent()
    {
        return $this->belongsTo(get_called_class(), 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(get_called_class(), 'parent_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\CatalogProduct', 'category_id');
    }

    /** Association with products
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function associatedProducts()
    {
        return $this->belongsToMany(
            'App\Models\CatalogProduct',
            'product_category_associations',
            'category_id',
            'product_id'
        );
    }

    /**
     * Product type pages, which has this category as manual.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function manualProductTypePages()
    {
        return $this->hasMany('App\Models\ProductTypePage', 'manual_product_list_category_id');
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
                'reviews_catalog_categories_associations',
                'catalog_categories_id',
                'reviews_id'
        );
    }


    protected static function boot()
    {
        parent::boot();

        self::mountUploader(
            'logo',
            UploaderIntegrator::getUploader(
                "uploads/catalog_categories/logo",
                ['thumb' => new BoxVersion(50, 35)]
            )
        );

        self::mountUploader(
            'logo_active',
            UploaderIntegrator::getUploader(
                "uploads/catalog_categories/logo_active",
                ['thumb' => new BoxVersion(50, 35)]
            )
        );

        self::deleting(
            function (self $category) {
                self::updateRelatedProductsOnDelete($category);

                // Delete child categories
                self::deleteRelatedAll($category->children());

                // Set to null relation to this category for product type pages
                foreach ($category->manualProductTypePages()->get() as $productTypePage) {
                    $productTypePage->manual_product_list_category_id = null;
                    $productTypePage->save();
                }

                // detatch related reviews
                $category->reviews()->detach();
            }
        );
    }

    private static function updateRelatedProductsOnDelete(CatalogCategory $category)
    {
        $category->associatedProducts()->detach();
        $categoryProducts = $category->products()->with('associatedCategories')->get();

        foreach ($categoryProducts as $product) {
            if ($product->associatedCategories->count() > 0) {
                $product->category_id = $product->associatedCategories[0]->id;
                $product->save();
            } else {
                $product->delete();
            }
        }
    }
}
