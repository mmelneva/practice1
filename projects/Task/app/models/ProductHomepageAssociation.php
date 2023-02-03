<?php namespace App\Models;

class ProductHomepageAssociation extends \Eloquent
{
    protected $table = 'product_home_page_associations';
    protected $fillable = ['position',];

    public function product()
    {
        return $this->belongsTo('App\Models\CatalogProduct', 'catalog_product_id');
    }

    public function homePage()
    {
        return $this->belongsTo('App\Models\HomePage', 'home_page_id');
    }
}
