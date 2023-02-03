<?php namespace App\Models;

use App\Models\Features\PasswordSetter;
use Diol\Laracl\IAclUser;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;

/**
 * App\Models\AdminUser
 *
 * @property integer $id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $username 
 * @property string $password 
 * @property string $remember_token 
 * @property boolean $active 
 * @property string $allowed_ips 
 * @property boolean $super 
 * @property integer $admin_role_id 
 * @property-read \App\Models\AdminRole $role 
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereAllowedIps($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereSuper($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\AdminUser whereAdminRoleId($value)
 */
class AdminUser extends \Eloquent implements UserInterface, IAclUser
{
    use UserTrait;
    use PasswordSetter;

    protected $hidden = ['password', 'remember_token'];
    protected $fillable = ['username', 'password', 'active', 'allowed_ips', 'admin_role_id'];

    /**
     * Set allowed ips attribute.
     *
     * @param array $value
     * @return array
     */
    public function setAllowedIpsAttribute(array $value)
    {
        $this->attributes['allowed_ips'] = implode("\n", $value);
        return $value;
    }

    /**
     * Get allowed ips attribute.
     *
     * @return array
     */
    public function getAllowedIpsAttribute()
    {
        $allowedIps = isset($this->attributes['allowed_ips']) ? $this->attributes['allowed_ips'] : '';
        return explode("\n", $allowedIps);
    }

    /**
     * Role.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('App\Models\AdminRole', 'admin_role_id');
    }

    /**
     * @inheritDoc
     */
    public function getRuleIdentifiers()
    {
        /** @var AdminRole $role */
        $role = $this->role()->first();
        if (is_null($role)) {
            $rules = [];
        } else {
            $rules = $role->rules;
        }

        return $rules;
    }

    /**
     * @inheritDoc
     */
    public function isSuper()
    {
        return (bool) $this->super;
    }
}
