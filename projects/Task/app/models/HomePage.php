<?php namespace App\Models;

use App\Models\Features\AttachedToNode;
use App\Models\Features\DeleteHelpers;

class HomePage extends \Eloquent
{
    use AttachedToNode;
    use DeleteHelpers;

    protected $fillable = [
        'header',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'content',
        'content_for_grid',
        'content_top',
        'order_icon_type',
    ];

    /**
     * Banners associated with home page.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function banners()
    {
        return $this->hasMany('App\Models\Banner', 'home_page_id');
    }

    /**
     * List of info about associated home pages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function homePageAssociations()
    {
        return $this->hasMany('App\Models\ProductHomepageAssociation', 'home_page_id');
    }

    protected static function boot()
    {
        parent::boot();

        self::deleting(
            function (self $model) {
                // Delete banners
                self::deleteRelatedAll($model->banners());

                self::deleteRelatedAll($model->homePageAssociations());
            }
        );
    }
}
