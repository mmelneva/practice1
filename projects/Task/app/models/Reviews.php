<?php namespace App\Models;


class Reviews extends \Eloquent
{
    protected $table = 'reviews';

    protected $fillable = [
        'name',
        'email',
        'comment',
        'answer',
        'publish',
        'date_at',
        'on_home_page',
        'product_id'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\CatalogProduct');
    }

    public function dateChanges()
    {
        return $this->hasMany('App\Models\ReviewsDateChange');
    }

    public function getDates()
    {
        return ['created_at', 'updated_at', 'date_at'];
    }

    /** Association with categories
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function associatedCategories()
    {
        return $this->belongsToMany(
                'App\Models\CatalogCategory',
                'reviews_catalog_categories_associations',
                'reviews_id',
                'catalog_categories_id'
        );
    }

    /** Association with categories
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function associatedProductTypePages()
    {
        return $this->belongsToMany(
                'App\Models\ProductTypePage',
                'reviews_product_type_pages_associations',
                'reviews_id',
                'product_type_pages_id'
        );
    }


    protected static function boot()
    {
        parent::boot();
        self::deleting(
                function (self $model) {
                    // detatch related categories
                    $model->associatedCategories()->detach();

                    // detatch related productTypePages
                    $model->associatedProductTypePages()->detach();
                }
        );
    }

}
