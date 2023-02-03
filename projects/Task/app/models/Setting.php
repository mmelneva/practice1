<?php namespace App\Models;

/**
 * Class Setting
 * @package App\Models
 */
class Setting extends \Eloquent
{
    protected $fillable = ['key', 'title', 'value', 'position', 'description', 'value_style'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo('App\Models\SettingGroup');
    }
}
