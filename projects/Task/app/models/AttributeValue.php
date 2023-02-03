<?php namespace App\Models;

use App\Models\Features\DeleteHelpers;

/**
 * Class AttributeValue
 *
 * @package App\Models
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $value
 * @property integer $allowed_value_id
 * @property integer $attribute_id
 * @property integer $product_id
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AttributeValue whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AttributeValue whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AttributeValue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AttributeValue whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AttributeValue whereAllowedValueId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AttributeValue whereAttributeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AttributeValue whereProductId($value)
 * @property-read \App\Models\CatalogProduct $product
 * @property-read \App\Models\Attribute $attribute
 * @property-read \App\Models\AttributeAllowedValue $allowedValue
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AttributeAllowedValue[] $multipleValues
 */
class AttributeValue extends \Eloquent
{
    use DeleteHelpers;

    protected $fillable = [
        'value',
        'allowed_value_id',
        'attribute_id',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\CatalogProduct', 'product_id');
    }

    public function attribute()
    {
        return $this->belongsTo('App\Models\Attribute');
    }

    public function allowedValue()
    {
        return $this->belongsTo('App\Models\AttributeAllowedValue', 'allowed_value_id');
    }

    public function multipleValues()
    {
        return $this->belongsToMany(
            'App\Models\AttributeAllowedValue',
            'attribute_multiple_values',
            'attribute_value_id',
            'allowed_value_id'
        );
    }

    public function getValueVariant()
    {
        switch ($this->attribute->type) {
            case Attribute::TYPE_LIST:
                return $this->allowedValue;
            case Attribute::TYPE_MULTIPLE_VALUES:
                return $this->multipleValues;
            default:
                return $this->value;
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(
            function (self $attributeValue) {
                // detach all multiple values from this attribute value
                $attributeValue->multipleValues()->detach();
            }
        );
    }
}
