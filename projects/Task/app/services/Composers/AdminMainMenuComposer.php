<?php namespace App\Services\Composers;

use App\Services\Admin\Menu\Menu;
use App\Services\Admin\Menu\MenuElement;
use App\Services\Admin\Menu\MenuGroup;
use Diol\Laracl\Acl;

/**
 * Class AdminMainMenuComposer
 * @package App\SocioCompass\Composers
 */
class AdminMainMenuComposer
{
    /**
     * Menu object.
     *
     * @var Menu
     */
    private $mainMenu;

    /**
     * Acl object.
     *
     * @var Acl
     */
    private $acl;

    public function __construct()
    {
        $this->mainMenu = \App::make('admin.main_menu');
        $this->acl = \App::make('admin_acl');
    }

    public function compose($view)
    {
        $menuData = [];
        foreach ($this->mainMenu->getMenuItems() as $menuItem) {
            if ($menuItem instanceof MenuElement) {
                $menuDataElement = $this->getMenuElementData($menuItem);
                if (!is_null($menuDataElement)) {
                    $menuData[] = $menuDataElement;
                }
            } elseif ($menuItem instanceof MenuGroup) {
                $groupMenuElement = [
                    'name' => $menuItem->getName(),
                    'icon' => $menuItem->getIcon(),
                    'elements' => [],
                ];

                $groupMenuElementActive = false;
                foreach ($menuItem->getMenuElementList() as $menuElement) {
                    $menuDataElement = $this->getMenuElementData($menuElement);
                    if (!is_null($menuDataElement)) {
                        $groupMenuElement['elements'][] = $menuDataElement;
                        $groupMenuElementActive = $groupMenuElementActive || $menuDataElement['active'];
                    }
                }
                $groupMenuElement['active'] = $groupMenuElementActive;
                if (count($groupMenuElement['elements']) > 0) {
                    $menuData[] = $groupMenuElement;
                }
            }
        }

        $view->with('main_menu', $menuData);
    }

    /**
     * Get menu element data for view
     *
     * @param MenuElement $menuElement
     * @return array
     */
    private function getMenuElementData(MenuElement $menuElement)
    {
        if ($this->acl->checkControllerAction($menuElement->getAction())) {
            $currentAction = \Route::getCurrentRoute()->getAction();
            if (isset($currentAction['controller'])) {
                $controllerName = explode('@', $currentAction['controller'])[0];
            } else {
                $controllerName = null;
            }

            $viewElement = [
                'name' => $menuElement->getName(),
                'icon' => $menuElement->getIcon(),
                'link' => action($menuElement->getAction()),
                'active' => in_array($controllerName, $menuElement->getControllerList()),
            ];
        } else {
            $viewElement = null;
        }

        return $viewElement;
    }
}
