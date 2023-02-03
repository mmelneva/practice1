<?php namespace App\Models;

use App\Models\Features\DeleteHelpers;

/**
 * Class CatalogProductAllowedValue
 *
 * @package App\Models
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $value
 * @property string $short_description
 * @property string $full_description
 * @property string $image
 * @property integer $position
 * @property integer $attribute_id
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AttributeAllowedValue whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AttributeAllowedValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AttributeAllowedValue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AttributeAllowedValue whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AttributeAllowedValue whereShortDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AttributeAllowedValue whereFullDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AttributeAllowedValue whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AttributeAllowedValue wherePosition($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AttributeAllowedValue whereAttributeId($value)
 * @property-read \App\Models\Attribute $attribute 
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AttributeAllowedValue[] $multipleValues
 */
class AttributeAllowedValue extends \Eloquent
{
    use DeleteHelpers;

    protected $fillable = [
        'value',
        'position',
        'attribute_id'
    ];

    public function attribute()
    {
        return $this->belongsTo('App\Models\Attribute');
    }

    public function attributeValue()
    {
        return $this->hasOne('App\Models\AttributeValue', 'allowed_value_id');
    }

    public function multipleValues()
    {
        return $this->belongsToMany(
            'App\Models\AttributeAllowedValue',
            'attribute_multiple_values',
            'allowed_value_id',
            'attribute_value_id'
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(
            function (self $attributeAllowedValue) {
                // detach all multiple values from this attribute allowed value
                $attributeAllowedValue->multipleValues()->detach();
                self::deleteRelatedAll($attributeAllowedValue->attributeValue());
            }
        );
    }
}
