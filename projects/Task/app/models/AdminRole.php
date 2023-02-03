<?php namespace App\Models;

use App\Models\Features\ArrayAttribute;

/**
 * Class AdminRole
 * @package App\SocioCompass\Models
 */
class AdminRole extends \Eloquent
{
    use ArrayAttribute;

    protected $fillable = ['name', 'rules'];

    /**
     * Setter for rules.
     *
     * @param array $value
     * @return array
     */
    public function setRulesAttribute($value)
    {
        return $this->setArrayAttribute('rules', $value);
    }

    /**
     * Getter for rules.
     *
     * @return array
     */
    public function getRulesAttribute()
    {
        return $this->getArrayAttribute('rules');
    }

    public function users()
    {
        return $this->hasMany('App\Models\AdminUser', 'admin_role_id');
    }

    public function allowedToDelete()
    {
        return $this->users()->count() == 0;
    }
}
