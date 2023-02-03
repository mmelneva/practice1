<?php namespace App\Models;

use App\Models\Features\AutoPublish;
use App\Models\Features\DeleteHelpers;

/**
 * App\Models\Node
 *
 * @property integer $id 
 * @property integer $parent_id 
 * @property string $alias 
 * @property string $name 
 * @property boolean $publish 
 * @property integer $position 
 * @property boolean $menu_top 
 * @property boolean $hide_regions_in_page
 * @property string $type
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \Illuminate\Database\Query\Builder|\App\Models\Node $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Node[] $children
 * @property-read \App\Models\TextPage $textPage 
 * @property-read \App\Models\HomePage $homePage 
 * @property-read \App\Models\MetaPage $metaPage 
 * @property-read \App\Models\ProductTypePage $productTypePage 
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Node whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Node whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Node whereAlias($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Node whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Node wherePublish($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Node wherePosition($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Node whereMenuTop($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Node whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Node whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Node whereUpdatedAt($value)
 */
class Node extends \Eloquent
{
    use DeleteHelpers;
    use AutoPublish;

    protected $fillable = [
        'parent_id',
        'alias',
        'name',
        'publish',
        'position',
        'type',
        'menu_top',
        'scrolled_menu_top',
        'menu_bottom',
        'hide_regions_in_page',
    ];

    public function parent()
    {
        return $this->belongsTo(get_called_class(), 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(get_called_class(), 'parent_id');
    }

    public function textPage()
    {
        return $this->hasOne('App\Models\TextPage');
    }

    public function homePage()
    {
        return $this->hasOne('App\Models\HomePage');
    }

    public function metaPage()
    {
        return $this->hasOne('App\Models\MetaPage');
    }

    public function productTypePage()
    {
        return $this->hasOne('App\Models\ProductTypePage');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(
            function (self $node) {
                // delete children
                self::deleteRelatedAll($node->children());

                // delete attached info pages
                self::deleteRelatedFirst($node->textPage());
                self::deleteRelatedFirst($node->homePage());
                self::deleteRelatedFirst($node->metaPage());
                self::deleteRelatedFirst($node->productTypePage());
            }
        );
    }
}
