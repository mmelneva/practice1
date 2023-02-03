<?php namespace App\Models;

use App\Models\Features\DeleteHelpers;

/**
 * Class Attribute
 *
 * @package App\Models
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $name
 * @property string $description
 * @property string $icon
 * @property string $type
 * @property integer $position
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CatalogProduct[] $products
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attribute whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attribute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attribute whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attribute whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attribute whereIcon($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attribute whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attribute wherePosition($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attribute wherePublish($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Attribute whereUseInFilter($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AttributeAllowedValue[] $allowedValues
 * @property-read mixed $is_string
 * @property-read mixed $is_list
 * @property-read mixed $is_multiple_values
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AttributeValue[] $values
 */
class Attribute extends \Eloquent
{
    use DeleteHelpers;

    const TYPE_STRING = 'string';
    const TYPE_LIST = 'list';
    const TYPE_MULTIPLE_VALUES = 'multiple_values';
    const TYPE_NUMBER = 'number';

    protected $fillable = [
        'name',
        'type',
        'position',
        'on_product_page',
        'use_in_similar_products',
        'similar_products_name',
    ];

    /**
     * Attribute has string type?
     *
     * @return bool
     */
    public function getIsStringAttribute()
    {
        return $this->type === self::TYPE_STRING;
    }

    /**
     * Attribute has number type?
     *
     * @return bool
     */
    public function getIsNumberAttribute()
    {
        return $this->type === self::TYPE_NUMBER;
    }

    /**
     * Attribute has list type?
     *
     * @return bool
     */
    public function getIsListAttribute()
    {
        return $this->type === self::TYPE_LIST;
    }

    /**
     * Attribute has multiple values type?
     *
     * @return bool
     */
    public function getIsMultipleValuesAttribute()
    {
        return $this->type === self::TYPE_MULTIPLE_VALUES;
    }

    public function values()
    {
        return $this->hasMany('App\Models\AttributeValue');
    }

    public function allowedValues()
    {
        return $this->hasMany('App\Models\AttributeAllowedValue');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(
            function (self $attribute) {
                // delete all values for this attribute
                self::deleteRelatedAll($attribute->values());

                // delete all allowed values for this attribute
                self::deleteRelatedAll($attribute->allowedValues());
            }
        );
    }
}
