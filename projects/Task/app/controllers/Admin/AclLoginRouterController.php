<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\Admin\Menu\Menu;
use Diol\Laracl\Acl;

/**
 * Class AclLoginRouterController
 * @package App\Controllers\Admin
 */
class AclLoginRouterController extends BaseController
{
    /**
     * @var Acl
     */
    private $acl;
    /**
     * @var Menu
     */
    private $mainMenu;

    public function __construct()
    {
        $this->acl = \App::make('admin_acl');
        $this->mainMenu = \App::make('admin.main_menu');
    }

    public function getRoute()
    {
        $actionToRedirect = null;

        foreach ($this->mainMenu->getFlattenMenu() as $menuElement) {
            $action = $menuElement->getAction();
            if ($this->acl->checkControllerAction($action)) {
                $actionToRedirect = $action;
                break;
            }
        }

        if (!is_null($actionToRedirect)) {
            return \Redirect::action($actionToRedirect);
        } else {
            return \Redirect::route('cc.home');
        }
    }
}
