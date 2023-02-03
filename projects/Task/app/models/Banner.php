<?php namespace App\Models;

use App\Models\Features\AutoPublish;
use Diol\Fileclip\Eloquent\Glue;
use Diol\Fileclip\UploaderIntegrator;
use Diol\Fileclip\Version\BoxVersion;
use Diol\FileclipExif\FileclipExif;

/**
 * App\Models\Banner
 *
 * @property integer $id 
 * @property integer $home_page_id 
 * @property string $name 
 * @property boolean $publish 
 * @property integer $position 
 * @property string $link 
 * @property string $image 
 * @property string $description 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \App\Models\HomePage $homePage 
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Banner whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Banner whereHomePageId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Banner whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Banner wherePublish($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Banner wherePosition($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Banner whereLink($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Banner whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Banner whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Banner whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Banner whereUpdatedAt($value)
 */
class Banner extends \Eloquent
{
    use Glue;
    use FileclipExif;
    use AutoPublish;

    protected $fillable = [
        'home_page_id',
        'name',
        'publish',
        'position',
        'link',
        'image_file',
        'image_remove',
        'description',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function homePage()
    {
        return $this->belongsTo('App\Models\HomePage', 'home_page_id');
    }

    protected static function boot()
    {
        parent::boot();

        self::mountUploader(
            'image',
            UploaderIntegrator::getUploader(
                "uploads/bnrs/image",
                [
                    'thumb' => new BoxVersion(50, 50),
                    'big' => new BoxVersion(940, 300),
                ]
            )
        );
    }
}
