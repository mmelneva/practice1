<?php namespace App\Models;

use App\Models\Features\DeleteHelpers;

/**
 * App\Models\Order
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $comment
 * @property string $order_status
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereComment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereOrderStatus($value)
 * @property integer $product_id 
 * @property string $product_name 
 * @property-read \App\Models\CatalogProduct $product 
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Order whereProductName($value)
 */
class Order extends \Eloquent
{
    use DeleteHelpers;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'comment',
        'order_status',
        'product_id',
        'product_name',
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\CatalogProduct');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(
            function (self $order) {
                $order->order_status = OrderStatusConstants::NOVEL;
            }
        );
    }
}
