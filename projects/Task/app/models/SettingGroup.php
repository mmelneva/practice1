<?php namespace App\Models;

/**
 * Class SettingGroup
 * @package App\Models
 */
class SettingGroup extends \Eloquent
{
    protected $fillable = ['name', 'position'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function settings()
    {
        return $this->hasMany('App\Models\Setting', 'group_id');
    }
}
