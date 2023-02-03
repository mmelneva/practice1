<?php namespace App\Services\Filters;

use App\Models\AdminUser;
use App;
use Auth;
use Diol\Laracl\Acl;
use Request;
use Redirect;
use Session;

/**
 * Class AuthAdminFilter
 * @package App\Filters
 */
class AuthAdminFilter
{
    /**
     * @var Acl
     */
    private $acl;

    public function __construct()
    {
        $this->acl = App::make('admin_acl');
    }

    public function filter()
    {
        $user = Auth::user();

        if ($user && $user->active && $this->userIsAllowedByIp($user)) {
            $this->acl->setCurrentUser(\Auth::user());
        } else {
            if (Request::isMethodSafe()) {
                return Redirect::guest(action('cc.login'));
            } else {
                $referrer = Request::server('HTTP_REFERER');
                if ($referrer) {
                    Session::set('url.intended', $referrer);
                }
                return Redirect::action('cc.login');
            }
        }
    }

    /**
     * Check if received user is allowed for current ip.
     *
     * @param AdminUser $user
     * @return bool
     */
    private function userIsAllowedByIp(AdminUser $user)
    {
        if (App::environment() == 'local') {
            return true;
        }

        if ($user->allowed_ips == ['']) {
            return true;
        }

        return in_array(Request::getClientIp(), $user->allowed_ips);
    }
}
