<?php namespace App\Models;

class ProductTypePageAssociation extends \Eloquent
{
    protected $fillable = ['name', 'manual', 'position', 'small_content'];

    public function product()
    {
        return $this->belongsTo('App\Models\CatalogProduct', 'catalog_product_id');
    }

    public function productTypePage()
    {
        return $this->belongsTo('App\Models\ProductTypePage', 'product_type_page_id');
    }
}
