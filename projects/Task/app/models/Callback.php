<?php namespace App\Models;

/**
 * App\Models\Callback
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $name
 * @property string $phone
 * @property string $comment
 * @property string $callback_status
 * @property string $url_referer
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Callback whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Callback whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Callback whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Callback whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Callback wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Callback whereComment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Callback whereCallbackStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Callback whereUrlReferer($value)
 * @property string $appropriate_time 
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Callback whereAppropriateTime($value)
 */
class Callback extends \Eloquent
{
    protected $fillable = [
        'name',
        'phone',
        'address',
        'comment',
        'callback_status',
        'url_referer',
        'appropriate_time',
        'type',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(
            function (self $order) {
                $order->callback_status = CallbackStatusConstants::NOVEL;
            }
        );
    }
}
