<?php namespace App\Services\Filters;

use Diol\Laracl\Acl;
use Illuminate\Routing\Route;

/**
 * Class AdminAclFilter
 * @package App\Services\Filters
 */
class AdminAclFilter
{
    /**
     * @var Acl
     */
    private $acl;

    public function __construct()
    {
        $this->acl = \App::make('admin_acl');
    }

    public function filter(Route $route)
    {
        if (!$this->acl->checkControllerAction($route->getAction()['controller'])) {
            \App::abort(403, 'User is not allowed for current route.');
        }
    }
}
